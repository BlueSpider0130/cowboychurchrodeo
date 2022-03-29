<?php
declare(strict_types=1);

namespace SfpResults;


interface RetrievableSfpParameters
{
    /**
     * @return string
     */
    public function getSearchParameterName() : string;
    
    /**
     * @return string
     */
    public function getFiltersParameterName(): string;
    
    /**
     * @return string
     */
    public function getSortParameterName(): string;
    
    /**
     * @return string
     */
    public function getSortDirectionParameterName(): string;

    /**
     * @return string
     */
    public function getPerPageCountParameterName(): string;

    /**
     * @return null | string
     */
    public function getSearch(): ?string;

    /**
     * @return null | array
     */
    public function getFilters(): ?array;

    /**
     * @return null | string
     */
    public function getSort(): ?string;

    /**
     * @return null | string
     */
    public function getSortDirection(): ?string;

    /**
     * @return null | int | string
     */
    public function getPerPageCount();

    /**
     * @return array
     */
    public function getParametersForQuerystring(): array;
}
