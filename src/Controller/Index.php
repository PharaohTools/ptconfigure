<?php

Namespace Controller ;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class Index extends Base {

    public function execute() {
        $content = array();
        return array ("view"=>"index", "pageVars"=>$content);
    }

}