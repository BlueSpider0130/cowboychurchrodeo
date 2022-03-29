<?php

namespace App\Services;

use App\Contracts\ListBuilder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\SearchQueryHelper;

/**
 * Use request querystring to build query for list of results.
 *
 * Example:
 *
 *      $searchable = [ 'name' ];
 *      $sortable = [ 'name' ];
 *
 *      $service->setSearchableAttributes( $searchable );
 *      $service->setSortableAttributes( $sortable );
 *       
 *      $results = $service->getResultsForModelClass( Organization::class );
 *
 *      // Or get results using query builder
 *      $query = Organization::select();
 *      $results = $service->getResultsForQuery( $query ); 
 *
 */
class ListBuilderService implements ListBuilder
{
    /**
     * @var \Illuminate\Http\Request\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $searchableAttributes = [];

    /**
     * @var array
     */
    protected $sortableAttributes = [];


    public function __construct( Request $request )
    {
        $this->setRequest( $request );
    }


    /**
     * Set the request to get data from.
     *
     * @param \Illuminate\Http\Request
     */
    public function setRequest( Request $request )
    {
        $this->request = $request;
    }


    /**
     * Get the set request.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * Set an array of attributes that can be searched by.
     * 
     * @param array 
     */
    public function setSearchableAttributes( array $attributes )
    {
        $this->searchableAttributes = $attributes;
    }


    /**
     * Get searchable attributes.
     *
     * @return array
     */
    public function getSearchableAttributes()
    {
        return $this->searchableAttributes;
    }


    /**
     * Set an array of attributes that can be sorted by.
     * 
     * @param array 
     */
    public function setSortableAttributes( array $attributes )
    {
        $this->sortableAttributes = $attributes;
    }


    /**
     * Get sortable attributes.
     *
     * @return array
     */
    public function getSortableAttributes()
    {
        return $this->sortableAttributes;
    }


    /**
     * Get search string from request.
     * 
     * @return string
     */
    public function getSearchStringFromRequest()
    {
        return $this->request->query( self::SEARCH_PARAM );
    }


    /**
     * Get sort data from request.
     *
     * @return array 
     */
    public function getSortDataFromRequest()
    {
        $attribute = $this->request->query( self::SORT_BY_PARAM );

        if( $attribute ) 
        {
            $direction = $this->request->query( self::SORT_DIRECTION_PARAM, 'asc' );

            $direction = in_array($direction, ['asc', 'desc'])  ?  $direction  :  'asc';

            return [
                'sort_by' => $attribute,
                'direction' => $direction
            ];
        }

        return null;
    }


    /**
     * Get the per page from request.
     * 
     * @return string
     */
    public function getPerPageFromRequest()
    {
        return $this->request->query( self::PER_PAGE_PARAM );
    }


    /**
     * Use model class and request data to get results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getResultsForModelClass( $class )
    {
        if( !is_subclass_of($class, Model::class) )
        {
            throw new \Exception("Class must be an Eloquent model.", 1);            
        }

        $query = call_user_func_array([$class, 'select'], []);

        return $this->getResultsForQuery( $query );
    }


    /**
     * Use query builder and request data to get results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getResultsForQuery( $query )
    {
        if( !is_a($query, \Illuminate\Database\Eloquent\Builder::class)  &&  !is_a($query, \Illuminate\Database\Eloquent\Relations\Relation::class) )
        {
            throw new \Exception("Query must be Illuminate\Database\Query\Builder or Illuminate\Database\Eloquent\Relations\Relation.", 1);            
        }

        $query = $this->buildSearch( $query );

        $query = $this->buildSort( $query );

        return $this->getPaginated( $query );
    }


    /**
     * Build search
     */
    public function buildSearch( $query )
    {
        if( !is_a($query, \Illuminate\Database\Eloquent\Builder::class)  &&  !is_a($query, \Illuminate\Database\Eloquent\Relations\Relation::class) )
        {
            throw new \Exception("Query must be Illuminate\Database\Query\Builder or Illuminate\Database\Eloquent\Relations\Relation.", 1);            
        }

        if( $searchString = $this->getSearchStringFromRequest() )
        {
            if( $this->searchableAttributes )
            {
                $query = SearchQueryHelper::buildQueryForSearchStringForColumns( $query, $searchString, $this->searchableAttributes );
            }
        }   

        return $query;     
    }


    /**
     * Build sort
     */
    public function buildSort( $query )
    {
        if( !is_a($query, \Illuminate\Database\Eloquent\Builder::class)  &&  !is_a($query, \Illuminate\Database\Eloquent\Relations\Relation::class) )
        {
            throw new \Exception("Query must be Illuminate\Database\Query\Builder or Illuminate\Database\Eloquent\Relations\Relation.", 1);            
        }

        if( $sortData = $this->getSortDataFromRequest() )
        {
            if( $this->sortableAttributes  &&  in_array($sortData['sort_by'], $this->sortableAttributes) )
            {
                $query->orderBy( $sortData['sort_by'], $sortData['direction'] );
            }
        }

        return $query;  
    } 


    /**
     * Get paginated results
     */
    public function getPaginated( $query )
    {
        if( !is_a($query, \Illuminate\Database\Eloquent\Builder::class)  &&  !is_a($query, \Illuminate\Database\Eloquent\Relations\Relation::class) )
        {
            throw new \Exception("Query must be Illuminate\Database\Query\Builder or Illuminate\Database\Eloquent\Relations\Relation.", 1);            
        }

        $perPage = $this->getPerPageFromRequest();

        if( 'all' == strtolower($perPage) )
        {
            $perPage = '18446744073709551615';      // use MySQL unsigned big int maximum; represent value as a string here to avoid issues with php max int size
        }

        if( !is_numeric($perPage) )
        {
            $perPage = null;
        }

        return $query->paginate( $perPage );
    }
}
