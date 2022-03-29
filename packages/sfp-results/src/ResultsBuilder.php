<?php 
declare(strict_types=1);

namespace SfpResults;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;

class ResultsBuilder 
{
    protected $request           = null;
    protected $searchable        = null;
    protected $searchType        = 'like';
    protected $filterable        = null;
    protected $sortable          = null;
    protected $defaultPagination = null;

    public function __construct( ResultsRequest $request )
    {
        $this->request = $request;
        $this->request->setSortDirections( ['asc', 'desc'] );
    }


    public function getRequest()
    {
        return $this->request;
    }


    public function setSearchable( array $searchable = null ): void
    {
        $this->searchable = $searchable;
    }


    public function getSerachable(): ?array
    {
        return $this->searchable;
    }


    public function setFilterable( array $filterable = null ): void 
    {
        $this->filterable = $filterable;
        $this->request->setFilterable( $filterable );
    }


    public function getFilterable(): ?array 
    {
        return $this->filterable;
    }


    public function setSortable( array $sortable = null ): void 
    {
        $this->sortable = $sortable;
        $this->request->setSortable( $sortable );
    }


    public function getSortable(): ?array
    {
        return $this->sortable;
    }


    public function setSearchType( string $type ): void 
    {
        $types = ['exact', 'equals', 'like'];

        if( ! in_array($type, $types) )
        {
            throw new \InvalidArgumentException("Search type must be of type: '".implode("', '", $types), 1);            
        }

        $this->searchType = $type;
    }


    public function getSearchType(): string
    {
        return $this->searchType;
    }


    public function setDefaultPagination( int $count ): void
    {
        $this->defaultPagination = $count;
    }


    public function getDefaultPagination(): ?int
    {
        return $this->defaultPagination;
    }

    public function getResultsForModel( string $class, array $with = [] ) : LengthAwarePaginator
    {
        if( ! class_exists($class) )
        {
            throw new \InvalidArgumentException("Class $class does not exist.", 1);            
        }

        if( ! is_subclass_of($class, Model::class) )
        {
            throw new \InvalidArgumentException("Class $class is not an eloquent model.", 1);            
        }

        $query = $class::with($with)->select();

        return $this->getResultsForQuery( $query );
    }


    /**
     * @param EloquentBuilder | QueryBuilder
     */
    public function getResultsForQuery( $query ) : LengthAwarePaginator
    {
        if( ! is_a($query, EloquentBuilder::class)  &&  ! is_a($query, QueryBuilder::class)  &&  ! is_a($query, Relation::class) )
        {
            throw new \InvalidArgumentException("Query must be of type ".EloquentBuilder::class." or ".QueryBuilder::class, 1);            
        }

        $query   = $this->applySearch( $query );
        $query   = $this->applyFilters( $query );
        $query   = $this->applySort( $query );
        $results = $this->paginate( $query );

        // append the list querystring params page links have needed querystring params
        $results->appends( $this->request->getParametersForQuerystring() );

        return $results;
    }


    /**
     * @param EloquentBuilder | QueryBuilder
     * @return EloquentBuilder | QueryBuilder
     */
    public function applySearch( $query )
    {
        if( ! is_a($query, EloquentBuilder::class)  &&  ! is_a($query, QueryBuilder::class)  &&  ! is_a($query, Relation::class) )
        {
            throw new \InvalidArgumentException("Query must be of type ".EloquentBuilder::class." or ".QueryBuilder::class, 1);            
        }

        $searchType  = $this->getSearchType();
        $searchable  = $this->getSerachable();        
        $searchValue = $this->request->getSearch();

        if( $this->searchable  &&  $searchValue )
        {
            $searchTerms = 'exact' == $searchType  ?  [$searchValue]  :  explode(' ', $searchValue);

            $query->where(function($q) use ($searchType, $searchable, $searchTerms) {

                $field = array_shift($searchable);

                if( 'exact' == $searchType  ||  'equals' == $searchType )
                {                    
                    $q->where($field, $searchTerms[0]);
                }
                else
                {
                    $q->where($field, 'like', "{$searchTerms[0]}%");
                }


                for ($i=1; $i < count($searchTerms); $i++) 
                { 
                    if( 'exact' == $searchType  ||  'equals' == $searchType )
                    {                    
                        $q->orWhere($field, $searchTerms[$i]);
                    }
                    else
                    {
                        $q->orWhere($field, 'like', "{$searchTerms[$i]}%");
                    }                    
                }

                foreach( $searchable as $field )
                {
                    foreach( $searchTerms as $term )
                    {
                        if( 'exact' == $searchType  ||  'equals' == $searchType )
                        {                    
                            $q->orWwhere($field, $term);
                        }
                        else
                        {
                            $q->orWhere($field, 'like', "{$term}%");
                        }                        
                    }
                }

                return $q;
            });
        }

        return $query;
    }


    /**
     * @param EloquentBuilder | QueryBuilder
     * @return EloquentBuilder | QueryBuilder
     */
    public function applyFilters( $query )
    {
        if( ! is_a($query, EloquentBuilder::class)  &&  ! is_a($query, QueryBuilder::class)  &&  ! is_a($query, Relation::class) )
        {
            throw new \InvalidArgumentException("Query must be of type ".EloquentBuilder::class." or ".QueryBuilder::class, 1);            
        }

        $filters    = $this->request->getFilters();
        $filterable = $this->getFilterable();

        if( $filters  &&  $filterable )
        {
            foreach( $filters as $filter => $value )
            {
                if( in_array($filter, $filterable) )
                {
                    $query->where($filter, $value);
                }
            }
        }

        return $query;
    }


    /**
     * @param EloquentBuilder | QueryBuilder
     * @return EloquentBuilder | QueryBuilder
     */
    public function applySort( $query, $defaultSort = null, $defaultDirection = 'asc' )
    {
        if( ! is_a($query, EloquentBuilder::class)  &&  ! is_a($query, QueryBuilder::class)  &&  ! is_a($query, Relation::class) )
        {
            throw new \InvalidArgumentException("Query must be of type ".EloquentBuilder::class." or ".QueryBuilder::class, 1);            
        }

        $sortable = $this->getSortable();
        $sort     = $this->request->getSort();

        if( ($sortable  &&  $sort  &&  in_array($sort, $sortable)) )
        {
            $direction = $this->request->getSortDirection();

            if( ! in_array($direction, ['asc', 'desc']) )
            {
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);
        }
        elseif( null != $defaultSort )
        {
            $query->orderBy($defaultSort, $defaultDirection);
        }

        return $query;
    }


    /**
     * @param EloquentBuilder | QueryBuilder
     */
    public function paginate( $query ) : LengthAwarePaginator
    {
        if( ! is_a($query, EloquentBuilder::class)  &&  ! is_a($query, QueryBuilder::class)  &&  ! is_a($query, Relation::class) )
        {
            throw new \InvalidArgumentException("Query must be of type ".EloquentBuilder::class." or ".QueryBuilder::class, 1);            
        }

        $perPageCount = $this->request->getPerPageCount();

        if( 'all' == $perPageCount )
        {
            $count = $query->count();
        }
        else
        {
            $count = $perPageCount ? $perPageCount : $this->getDefaultPagination();
        }

        $results = $query->paginate( $count );

        $results->appends( $this->request->getParametersForQuerystring() );

        return $results;
    }
  
}