<?php

cukeExecutor::execute();

class cukeExecutor {

    public static function execute(){
        self::setWorkingDirectory();
        self::performTests(); }

    private function setWorkingDirectory(){
        $scriptLocation = dirname(__FILE__).'/../../tests/';
        chdir($scriptLocation); }

    private function performTests(){
        $command = 'cucumber';
        self::executeAndOutput($command); }

    private static function executeAndOutput($command) {
        $outputArray = array();
        exec($command, $outputArray);
        echo "\nOutput for Command $command:\n";
        foreach ($outputArray as $outputValue) {
            echo "$outputValue\n"; } }

}

?>