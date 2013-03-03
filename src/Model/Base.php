<?php

Namespace Model;

class Base {

    protected function executeAndOutput($command, $message=null) {
        $outputArray = array();
        exec($command, $outputArray);
        $outputText = "";
        foreach ($outputArray as $outputValue) {
            $outputText .= "$outputValue\n"; }
        if ($message !== null) {
            $outputText .= "$message\n"; }
        print $outputText;
        return true;
    }

    protected function executeAndLoad($command) {
        $outputArray = array();
        exec($command, $outputArray);
        $outputText = "";
        foreach ($outputArray as $outputValue) {
            $outputText .= "$outputValue\n"; }
        return $outputText;
    }

    protected function askYesOrNo($question) {
        print "$question (Y/N) \n";
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        while (!$last_line) {
            $inputChar = fgetc($fp);
            $yesOrNo = ($inputChar=="y"||$inputChar=="Y") ? true : false;
            $last_line = true; }
        return $yesOrNo;
    }

    protected function areYouSure($question) {
        print "!! Sure? $question (Y/N) !!\n";
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        while (!$last_line) {
            $inputChar = fgetc($fp);
            $yesOrNo = ($inputChar=="y"||$inputChar=="Y") ? true : false;
            $last_line = true; }
        return $yesOrNo;
    }

    protected function askForDigit($question) {
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        $i = 0;
        while ($last_line == false ) {
            print "$question\n";
            $inputChar = fgetc($fp);
            if (in_array($inputChar, array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9")) ) { $last_line = true; }
            else { echo "You must enter a single digit. Please try again\n"; continue; }
        $i++; }
        return $inputChar;
    }

    protected function askForInput($question, $required=null) {
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        while (!$last_line) {
            print "$question\n";
            $inputLine = fgets($fp, 1024);
            if ($required && strlen($inputLine)==0 ) {
                print "You must enter a value. Please try again.\n"; }
            else {$last_line = true;} }
        $inputLine = $this->stripNewLines($inputLine);
        return $inputLine;
    }

    private function stripNewLines($inputLine) {
        $inputLine = str_replace("\n", "", $inputLine);
        $inputLine = str_replace("\r", "", $inputLine);
        return $inputLine;
    }



}