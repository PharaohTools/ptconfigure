<?php

Use \Core\autoLoader;

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
        require_once(dirname(__FILE__) . "/../../../src/AutoLoad.php");
        $autoLoader = new autoLoader();
        $autoLoader->launch();
    }

    public static function getMysqlI() {
        return new \mysqli("localhost", "root", "ebayebay", "ebaycodepractice");
    }

}