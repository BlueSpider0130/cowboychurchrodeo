<?php
declare(strict_types=1);

namespace SfpResults;

use Illuminate\Http\Request;


class ResultsRequest extends Request implements RetrievableSfpParameters 
{
    protected $searchParameterName        = 'search';
    protected $filtersParameterName       = 'filters';
    protected $sortParameterName          = 'sort-by';
    protected $sortDirectionParameterName = 'sort-direction';
    protected $perPageCountParameterName  = 'results-per-page';
    
    protected $filterable     = null;
    protected $sortable       = null;
    protected $sortDirections = ['asc', 'desc'];


    public function setSearchParameterName( string $name ): void
    {
        $this->searchParameterName = $name;
    }

    public function getSearchParameterName() : string
    {
        return $this->searchParameterName;
    }

    public function setFiltersParameterName( string $name ): void
    {
        $this->filtersParameterName = $name;
    }

    public function getFiltersParameterName(): string
    {
        return $this->filtersParameterName;
    }

    public function setSortParameterName( string $name ): void
    {
        $this->sortParameterName = $name;
    }

    public function getSortParameterName(): string
    {
        return $this->sortParameterName;
    }

    public function setSortDirectionParameterName( string $name ): void
    {
        $this->sortDirectionParameterName = $name;
    }

    public function getSortDirectionParameterName(): string
    {
        return $this->sortDirectionParameterName;
    }

    public function setPerPageCountParameterName( string $name ): void
    {
        $this->perPageCountParameterName = $name;
    }

    public function getPerPageCountParameterName(): string
    {
        return $this->perPageCountParameterName;
    }


    public function setFilterable( array $filterable ): void
    {
        $this->filterable = $filterable;
    }

    public function getFilterable(): ?array
    {
        return $this->filterable;
    }

    public function setSortable( array $sortable ): void 
    {
        $this->sortable = $sortable;
    }

    public function getSortable(): ?array
    {
        return $this->sortable;
    }

    public function setSortDirections( array $directions ): void 
    {
        $this->sortDirections = $directions;
    }

    public function getSortDirections(): ? array
    {
        return $this->sortDirections;
    }


    /**
     * return null | string
     */
    public function getSearch(): ?string
    {
        return $this->query( $this->getSearchParameterName() );
    }


    /**
     * return null | array
     */
    public function getFilters(): ?array
    {
        $filters = $this->query( $this->getFiltersParameterName() );

        if( ! is_array($filters) )
        {
            return null;
        }

        if( $filters  &&  $this->getFilterable() )
        {
            foreach( array_keys($filters) as $key )
            {
                if( ! in_array($key, $this->getFilterable()) )
                {
                    unset($filters[$key]);
                }
            }
        }

        return $filters;
    }


    /**
     * return null | string
     */
    public function getSort(): ?string
    {
        $sort = $this->query( $this->getSortParameterName() );

        if( $sort  &&  $this->getSortable() )
        {
            return in_array($sort, $this->getSortable() )
                    ?  $sort 
                    :  null;
        }

        return $sort;
    }


    /**
     * return null | string
     */
    public function getSortDirection(): ?string
    {
        $direction = $this->query( $this->getSortDirectionParameterName() );

        return in_array($direction, $this->getSortDirections())  
                ?  $direction  
                :  null;
    }


    /**
     * @return null | int | string
     */
    public function getPerPageCount()
    {
        $perPageCount = $this->query( $this->getPerPageCountParameterName() );

        if( $perPageCount  &&  is_numeric($perPageCount) )
        {
            $perPageCount = (int) $perPageCount;

            return $perPageCount > 0  ?  $perPageCount  :  null;
        }

        return $perPageCount  &&  'all' == strtolower($perPageCount) 
                ? 'all' 
                :  null;
    }


    /**
     * @return array
     */
    public function getParametersForQuerystring( array $additionalParameters = [] ): array
    {
        $parameters = [];

        if( $value = $this->getSearch() )
        {
            $parameters[ $this->getSearchParameterName() ] = $value;
        }

        if( $value = $this->getFilters() )
        {
            $parameters[ $this->getFiltersParameterName() ] = $value;
        }

        if( $value = $this->getSort() )
        {
            $parameters[ $this->getSortParameterName() ] = $value;
        }

        if( $value = $this->getSortDirection() )
        {
            $parameters[ $this->getSortDirectionParameterName() ] = $value;
        }

        if( $value = $this->getPerPageCount() )
        {
            $parameters[ $this->getPerPageCountParameterName() ] = $value;
        }

        return array_merge($parameters, $additionalParameters);
    }
}
