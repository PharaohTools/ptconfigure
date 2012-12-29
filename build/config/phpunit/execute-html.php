<?php

phpUnitHtmlExecutor::execute();

class phpUnitHtmlExecutor {

    public static function execute(){
        self::setWorkingDirectory();
        self::performUnitTests(); }

    private function setWorkingDirectory(){
        $scriptLocation = dirname(__FILE__);
        chdir($scriptLocation); }

    private function performUnitTests(){
        $command = 'phpunit --coverage-html ../../reports/phpunit/html/ ../../tests/phpunit/';
        self::executeAndOutput($command); }

    private static function executeAndOutput($command) {
        $outputArray = array();
        exec($command, $outputArray);
        echo "\nOutput for Command $command:\n";
        foreach ($outputArray as $outputValue) {
            echo "$outputValue\n"; } }

}

?>