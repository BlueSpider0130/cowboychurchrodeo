<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class SearchQueryHelper
{
    /**
     * Parse a string for keywords and build or/like query for the searchable columns.
     */
    static function buildQueryForSearchStringForColumns( $baseQuery, string $string, array $columns )
    {
        $string = trim($string);

        if( strlen($string) < 1 )
        {
            return $query;
        }

        return $baseQuery->where( function($query) use($string, $columns) {

            // process AND logic
            if( $substrings = static::getAndSubstrings( $string ) )
            {
                return static::buildAndQuery( $query, $substrings, $columns );
            }

            // process OR logic
            if( $substrings = static::getOrSubstrings( $string ) )
            {
                return static::buildOrQuery( $query, $substrings, $columns );
            }

            // search all columns for full string 
            foreach( $columns as $column )
            {
                $query = $query->orWhere($column, $string);
            }

            // get keywords to search for in columns, 
            // or search single column when = present in substrings
            $keywords = [];

            foreach( explode(',', $string) as $substring )
            {
                $substring = trim($substring);

                if( strlen($substring) > 0 )
                {
                    if( 1 == substr_count($substring, '=')  &&  false !== strpos($substring, '=')  &&  strlen(str_replace(' ', '', $substring)) >= 3 )
                    {
                        $parts = explode('=', $substring);
                        $column = trim($parts[0]);
                        $keyword = trim($parts[1]);

                        if( !in_array($column, $columns) )
                        {
                            $column = strtolower(str_replace(' ', '_', $column));
                        }

                        if( !in_array($column, $columns) )
                        {
                            $keywords = array_merge($keywords, explode(' ', $substring) );
                        }
                        else
                        {
                            $query = $query->orWhere($column, 'like', "%{$keyword}%");
                        }
                    }
                    else
                    {
                        $keywords = array_merge($keywords, explode(' ', $substring) );
                    }
                }
            }

            // search all columns for keywords 
            $query = static::buildKeywordQuery( $query, $keywords, $columns );

            return $query;

        });        
    }


    /**
     * Search for keywords in columns using OR and LIKE
     */
    static function buildKeywordQuery( $query, array $keywords, array $columns )
    {
        foreach ($keywords as $keyword) 
        {
            $keyword = trim($keyword);

            if( strlen($keyword) > 0 )
            {
                foreach ($columns as $column) 
                {
                    $query = $query->orWhere($column, 'like', "%{$keyword}%");
                }
            }
        }

        return $query;
    }


    /**
     * Get AND substrings from string.
     */
    static function getAndSubstrings( string $string )    
    {
        $string = str_ireplace(' :AND ', '&&', $string);

        if( false !== strpos($string, '&&') )
        {
            return explode('&&', $string);
        }

        return null;
    }


    /**
     * Process AND substrings
     */
    static function buildAndQuery( $query, array $substrings, array $columns )
    {
        return $query->orWhere( function($q2) use ($substrings, $columns) {

            foreach( $substrings as $substring )
            {
                $substring = trim($substring);

                if( strlen($substring) > 0 )
                {
                    $q2 = $q2->where( function($q3) use ($substring, $columns) {

                        return static::stringSearchOnColumns( $q3, $substring, $columns );

                    });
                }
            }

            return $q2;
        });          
    }


    /**
     * Get OR substrings from string.
     */
    static function getOrSubstrings( string $string )    
    {
        $string = str_ireplace(' :OR ', '||', $string);

        if( false !== strpos($string, '||') )
        {
            return explode('||', $string);
        }

        return null;
    }


    /**
     * Process OR substrings
     */
    static function buildOrQuery( $query, array $substrings, array $columns )
    {
        return $query->orWhere( function($q2) use ($substrings, $columns) {

            foreach( $substrings as $substring )
            {
                $substring = trim($substring);

                if( strlen($substring) > 0 )
                {
                    $q2 = $q2->orWhere( function($q3) use ($substring, $columns) {

                        return static::stringSearchOnColumns( $q3, $substring, $columns );

                    });
                }
            }

            return $q2;
        });     
    }    

       
}
