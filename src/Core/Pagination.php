<?php

Namespace Core ;

class Pagination {

    private $currentPage;
    private $pageSize;

    public function __construct() {
        $this->setPagination();
        $this->pageSize = 10;
    }

    private function setPagination() {
        $dataHelpers = new DatabaseHelpers();
        $pageNumRequest = (isset($_REQUEST["ecpPage"])) ? $dataHelpers->sanitize($_REQUEST["ecpPage"]) : null ;
        if ( is_int($pageNumRequest) && $pageNumRequest>0 ) {
            $this->currentPage = $pageNumRequest; }
        else {
            $this->currentPage = 1; }
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getPageSize() {
        return $this->pageSize;
    }

}