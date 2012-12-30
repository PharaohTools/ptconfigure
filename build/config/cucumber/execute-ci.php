<?php

cukeCiExecutor::execute();

class cukeCiExecutor {

    public static function execute(){
        self::setWorkingDirectory();
        self::startRuby();
        self::performTests();
    }

    private function setWorkingDirectory(){
        $basePath = str_replace('build/config/cucumber', "", dirname(__FILE__));
        $scriptLocation = $basePath.'build/tests/';
        $command = "cd $scriptLocation";
        self::executeAndOutput($command); }

    private function performTests(){
        $command = 'cucumber --format json -o cucumber.json';
        self::executeAndOutput($command); }

    private static function executeAndOutput($command) {
        $outputArray = array();
        exec($command, $outputArray);
        echo "\nOutput for Command $command:\n";
        foreach ($outputArray as $outputValue) {
            echo "$outputValue\n"; } }

}

?>