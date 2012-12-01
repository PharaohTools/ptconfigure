<?php

Namespace Core;

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

$bootStrap = new bootStrapForTests();
$bootStrap->launch();

class bootStrapForTests {

    public function launch() {
        require_once("../src/autoLoad.php");
        $autoLoader = new autoLoader();
        $autoLoader->launch();
    }

}