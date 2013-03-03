<?php

Namespace Model;

class GitCheckout {


    private function performUnitTests(){
        $command = 'phpunit ../../tests/phpunit/';
        return self::executeAndOutput($command);
    }

    private function executeAndOutput($command) {
        $outputArray = array();
        exec($command, $outputArray);
        $outputText = "";
        foreach ($outputArray as $outputValue) {
            $outputText .= "$outputValue\n"; }
        return $outputText;
    }

}