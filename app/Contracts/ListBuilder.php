<?php

namespace App\Contracts;

use Illuminate\Http\Request;

/**
 * Use Request parameters to build query (search, sort, etc.) and get paginated results.
 * 
 * example use:
 *       
 *      // where $service is an object of class implementing this interface
 *
 *      $searchable = [ 'first_name', 'last_name' ];
 *      $sortable = [ 'first_name', 'last_name', 'created_at' ];
 *
 *      $service->setSearchableAttributes( $searchable );
 *      $service->setSortableAttributes( $sortable );
 *
 *      // get searched, sorted, paginated results
 *      $results = $service->getResultsForClass( \App\User::class );
 *  
 *      // or instead get results using a query builder object
 *      $query = \App\Contestant::select();
 *      $results = $service->getResultsForQuery( $query );
 *
*/
interface ListBuilder
{
    const SEARCH_PARAM         = 'search';
    const SORT_BY_PARAM        = 'sort_by';
    const SORT_DIRECTION_PARAM = 'sort_direction';
    const PER_PAGE_PARAM       = 'per_page';

    
    /**
     * Set the request to get data from.
     *
     * @param \Illuminate\Http\Request
     */
    function setRequest( Request $request );

    /**
     * Get the set request.
     *
     * @return \Illuminate\Http\Request
     */
    function getRequest();

    /**
     * Set an array of attributes that can be searched by.
     * 
     * @param array 
     */
    function setSearchableAttributes( array $attributes );

    /**
     * Get searchable attributes.
     *
     * @return array
     */
    function getSearchableAttributes();

    /**
     * Set an array of attributes that can be sorted by.
     * 
     * @param array 
     */
    function setSortableAttributes( array $attributes );

    /**
     * Get sortable attributes.
     *
     * @return array
     */
    function getSortableAttributes();


    /**
     * Get search string from request.
     * 
     * @return string
     */
    function getSearchStringFromRequest();


    /**
     * Get sort data from request.
     *
     * @return array 
     */
    function getSortDataFromRequest();


    /**
     * Get the per page from request.
     * 
     * @return string
     */
    function getPerPageFromRequest();

    /**
     * Use model class and request data to get results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function getResultsForModelClass( $class );


    /**
     * Use query builder and request data to get results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function getResultsForQuery( $query );

}
