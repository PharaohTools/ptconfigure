<?php

Namespace Model;

class Base {

    protected $params ;
    protected $baseTempDir ;

    public function __construct() {
      $tempDirInConfig = \Model\AppConfig::getAppVariable("temp-directory") ;
      $tempDirInConfig = (substr($tempDirInConfig, -1, 1) == "/") ?
        substr($tempDirInConfig, 0, strlen($tempDirInConfig)-1) : $tempDirInConfig ;
      $this->baseTempDir = ($tempDirInConfig == null ) ? "/tmp/dapperstrano" : $tempDirInConfig ;
    }

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

    protected function setCmdLineParams($params) {
        $cmdParams = array();
        foreach ($params as $param) {
            if ( substr($param, 0, 2)=="--" && strpos($param, '=') != null ) {
                $equalsPos = strpos($param, "=") ;
                $paramKey = substr($param, 2, $equalsPos-2) ;
                $paramValue = substr($param, $equalsPos+1, strlen($param)) ;
                $cmdParams = array_merge($cmdParams, array($paramKey => $paramValue)); } }
        $this->params = $cmdParams;
    }

    protected function askYesOrNo($question) {
        print "$question (Y/N)\n";
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        while (!$last_line) {
            $inputChar = fgetc($fp);
            $yesOrNo = ($inputChar=="y"||$inputChar=="Y") ? true : false;
            $last_line = true; }
        return (isset($yesOrNo)) ? $yesOrNo : false;
    }

    protected function areYouSure($question) {
        print "!! Sure? $question (Y/N) !!\n";
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        while (!$last_line) {
            $inputChar = fgetc($fp);
            $yesOrNo = ($inputChar=="y"||$inputChar=="Y") ? true : false;
          $last_line = true; }
      return (isset($yesOrNo)) ? $yesOrNo : false;
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
        return (isset($inputChar)) ? $inputChar : false;
    }

    protected function askForInput($question, $required=null) {
      $fp = fopen('php://stdin', 'r');
      $last_line = false;
      while (!$last_line) {
        print "$question\n";
        $inputLine = fgets($fp, 1024);
        if ($required != null && ($inputLine=="" || $inputLine=="\n" || $inputLine=="\r" ) ) {
          print "You must enter a value. Please try again.\n"; }
        else {$last_line = true;} }
      $inputLine = $this->stripNewLines($inputLine);
      return $inputLine;
    }

    protected function askForInteger($question, $required=null) {
      $fp = fopen('php://stdin', 'r');
      $last_line = false;
      while (!$last_line) {
        print "$question\n";
        $inputLine = fgets($fp, 1024);
        $inputLine = str_replace( "\n", "", $inputLine);
        if ($required != null && ($inputLine=="" || $inputLine=="\n" || $inputLine=="\r" ) ) {
          print "You must enter a value. Please try again.\n"; }
        else if ( !is_numeric($inputLine) ) {
          print "You must enter an Integer value. Please try again.\n"; }
        else {$last_line = true;} }
      $inputLine = $this->stripNewLines($inputLine);
      return $inputLine;
    }

    protected function askForArrayOption($question, $options, $required=null) {
        $fp = fopen('php://stdin', 'r');
        $last_line = false;
        while ($last_line == false) {
            print "$question\n";
            for ( $i=0 ; $i<count($options) ; $i++) { print "($i) $options[$i] \n"; }
            $inputLine = fgets($fp, 1024);
            if ($required && strlen($inputLine)==0 ) { print "You must enter a value. Please try again.\n"; }
            elseif ( is_int($inputLine) && ($inputLine>=0) && ($inputLine<=count($options) ) ) {
                print "Enter one of the given options. Please try again.\n"; }
            else {$last_line = true; } }
        $inputLine = $this->stripNewLines($inputLine);
        return (isset($options[$inputLine])) ? $options[$inputLine] : "" ;
    }

    protected function stripNewLines($inputLine) {
        $inputLine = str_replace("\n", "", $inputLine);
        $inputLine = str_replace("\r", "", $inputLine);
        return $inputLine;
    }



}