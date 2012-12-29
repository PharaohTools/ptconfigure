<?php

cukeConsoleExecutor::execute();

class cukeConsoleExecutor {

    public static function execute(){
        self::setWorkingDirectory();
        self::startRuby();
        self::performTests(); }

    private function setWorkingDirectory(){
        $scriptLocation = dirname(__FILE__).'/../../tests/';
        chdir($scriptLocation); }

    private function startRuby(){
        $starter = 'rvm use ~/.rvm/bin/ruby';
        self::executeAndOutput($starter); }

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