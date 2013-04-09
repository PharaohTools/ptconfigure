<?php

Use \Core\autoLoader;

$bootStrap = new bootStrapForTests();
$bootStrap->launch();

class bootStrapForTests {

    public function launch() {
        require_once(dirname(__FILE__) . "/../../../src/AutoLoad.php");
        $autoLoader = new autoLoader();
        $autoLoader->launch();
    }

}