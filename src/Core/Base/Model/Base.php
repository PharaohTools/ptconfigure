<?php

Namespace Model;

class Base {

    public $params ;

    public $autopilotDefiner ;
    public $programNameFriendly;
    public $programNameInstaller;

    protected $installUserName;
    protected $installUserHomeDir;

    protected $registeredPreInstallFunctions;
    protected $registeredPreUnInstallFunctions;
    protected $registeredPostInstallFunctions;
    protected $registeredPostUnInstallFunctions;

    protected $programNameMachine ;
    protected $programDataFolder;
    protected $startDirectory;
    protected $titleData;
    protected $completionData;
    protected $bootStrapData;
    protected $extraBootStrap;
    protected $programExecutorFolder;
    protected $programExecutorTargetPath;
    protected $extraCommandsArray;
    protected $tempDir;

    public function __construct($params) {
      $this->tempDir =  DIRECTORY_SEPARATOR.'tmp';
      $this->setCmdLineParams($params);
    }

    protected function populateTitle() {
        $this->titleData = <<<TITLE
*******************************
*   Golden Contact Computing  *
*         $this->programNameFriendly        *
*******************************

TITLE;
    }

    protected function populateCompletion() {
        $this->completionData = <<<COMPLETION
... All done!
*******************************
Thanks for installing , visit www.gcsoftshop.co.uk for more

COMPLETION;
    }

    protected function setAutoPilotVariables($autoPilot) {
        foreach ( $autoPilot as $step ) { // this should only happen once
            $keys = array_keys($step);
            foreach ($keys as $property) {
                $this->$property = $step[$property] ; } }
    }

    protected function executeAsShell($multiLineCommand, $message=null) {
        $tempFile = $this->tempDir."/cleopatra-temp-script-".mt_rand(100, 99999999999).".sh";
        echo "Creating $tempFile\n";
        $fileVar = "";
        if (is_array($multiLineCommand) && count($multiLineCommand)>0) {
            foreach ($multiLineCommand as $command) { $fileVar .= $command."\n" ; } }
        file_put_contents($tempFile, $fileVar);
        echo "chmod 755 $tempFile 2>/dev/null\n";
        shell_exec("chmod 755 $tempFile 2>/dev/null");
        echo "Changing $tempFile Permissions\n";
        echo "Executing $tempFile\n";
        $outputText = shell_exec($tempFile);
        if ($message !== null) { $outputText .= "$message\n"; }
        echo $outputText;
        shell_exec("rm $tempFile");
        echo "Temp File $tempFile Removed\n";
    }

    protected function executeAndOutput($command, $message=null) {
        $outputText = shell_exec($command);
        if ($message !== null) {
          $outputText .= "$message\n"; }
        print $outputText;
        return true;
    }

    protected function executeAndLoad($command) {
        $outputText = shell_exec($command);
        return $outputText;
    }

    public static function executeAndGetReturnCode($command) {
        $output = '';
        $retVal = null;
        exec($command, $output, $retVal);
        return $retVal;
    }

    protected function setCmdLineParams($params) {
        $cmdParams = array();
        foreach ($params as $paramKey => $paramValue) {
            if (substr($paramValue, 0, 2)=="--" && strpos($paramValue, '=') != null ) {
                $equalsPos = strpos($paramValue, "=") ;
                $paramKey = substr($paramValue, 2, $equalsPos-2) ;
                $paramValue = substr($paramValue, $equalsPos+1, strlen($paramValue)) ; }
            else if (substr($paramValue, 0, 2)=="--" && strpos($paramValue, '=') == false ) {
                $paramKey = substr($paramValue, 2) ;
                $paramValue = true ; }
            $cmdParams = array_merge($cmdParams, array($paramKey => $paramValue)); }
        $this->params = (is_array($this->params)) ? array_merge($this->params, $cmdParams) : $cmdParams;
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
        return (isset($options[$inputLine])) ? $options[$inputLine] : null ;
    }

    protected function stripNewLines($inputLine) {
        $inputLine = str_replace("\n", "", $inputLine);
        $inputLine = str_replace("\r", "", $inputLine);
        return $inputLine;
    }

    protected function findStatusByDirectory($inputLine) {
        $inputLine = str_replace("\n", "", $inputLine);
        $inputLine = str_replace("\r", "", $inputLine);
        return $inputLine;
    }

    protected function setInstallFlagStatus($bool) {
        if ($bool) {
            AppConfig::setProjectVariable("installed-apps", $this->programNameMachine, true); }
        else {
            AppConfig::deleteProjectVariable("installed-apps", "any", $this->programNameMachine); }
    }

    public function askStatus() {
        return $this->getInstallFlagStatus($this->programNameMachine) ;
    }

    protected function getInstallFlagStatus($programNameMachine) {
        $installedApps = AppConfig::getProjectVariable("installed-apps");
        if (is_array($installedApps) && in_array($programNameMachine, $installedApps)) {
            return true ; }
        return false ;
    }

    protected function extraCommands(){
        self::swapCommandArrayPlaceHolders($this->extraCommandsArray);
        self::executeAsShell($this->extraCommandsArray);
    }

    protected function swapCommandArrayPlaceHolders(&$commandArray) {
        $this->swapCommandDirs($commandArray);
        $this->swapInstallUserDetails($commandArray);
    }

    protected function swapCommandDirs(&$commandArray) {
        if (is_array($commandArray) && count($commandArray)>0) {
            foreach ($commandArray as &$comm) {
                $comm = str_replace("****PROGDIR****", $this->programDataFolder, $comm);
                $comm = str_replace("****PROG EXECUTOR****", $this->programExecutorTargetPath, $comm); } }
    }

    protected function swapInstallUserDetails(&$commandArray) {
        if (is_array($commandArray) && count($commandArray)>0) {
            foreach ($commandArray as &$comm) {
                $comm = str_replace("****INSTALL USER NAME****", $this->installUserName,
                    $comm);
                $comm = str_replace("****INSTALL USER HOME DIR****",
                    $this->installUserHomeDir, $comm); } }
    }

    protected function executePreInstallFunctions($autoPilot){
        if (isset($this->registeredPreInstallFunctions) &&
            is_array($this->registeredPreInstallFunctions) &&
            count($this->registeredPreInstallFunctions) >0) {
            foreach ($this->registeredPreInstallFunctions as $func) {
                $this->$func($autoPilot); } }
    }

    protected function executePostInstallFunctions($autoPilot){
        if (isset($this->registeredPostInstallFunctions) &&
            is_array($this->registeredPostInstallFunctions) &&
            count($this->registeredPostInstallFunctions) >0) {
            foreach ($this->registeredPostInstallFunctions as $func) {
                $this->$func($autoPilot); } }
    }

    protected function executePreUnInstallFunctions($autoPilot){
        if (isset($this->registeredPreUnInstallFunctions) &&
            is_array($this->registeredPreUnInstallFunctions) &&
            count($this->registeredPreUnInstallFunctions) >0) {
            foreach ($this->registeredPreUnInstallFunctions as $func) {
                $this->$func($autoPilot); } }
    }

    protected function executePostUnInstallFunctions($autoPilot){
        if (isset($this->registeredPostUnInstallFunctions) &&
            is_array($this->registeredPostUnInstallFunctions) &&
            count($this->registeredPostUnInstallFunctions) >0) {
            foreach ($this->registeredPostUnInstallFunctions as $func) {
                $this->$func($autoPilot); } }
    }

    public function askAction($action) {
        return $this->askWhetherToDoAction($action);
    }

    protected function askWhetherToPerformActionToScreen($action){
        $question = "Perform ".$this->programNameInstaller." $action?";
        return self::askYesOrNo($question);
    }

    protected function performAction($action) {
        $doAction = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToPerformActionToScreen($action);
        if (!$doAction) { return false; }
        return $this->installAction($action);
    }

    // @todo refactor this 14 lines of ugliness
    protected function askWhetherToDoAction($action) {
        if ( isset($this->actionsToMethods)) {
            if (method_exists($this, $this->actionsToMethods[$action])) {
                $return = $this->{$this->actionsToMethods[$action]}() ;
                return $return ; }
            else {
                $consoleFactory = new \Model\Console();
                $console = $consoleFactory->getModel($this->params);
                $console->log("No method {$this->actionsToMethods[$action]} in model ".get_class($this)) ;
                return false; } }
        else {
            $consoleFactory = new \Model\Console();
            $console = $consoleFactory->getModel($this->params);
            $console->log('No property $actionsToMethods in model '.get_class($this)) ;
            return false; }
    }


}
