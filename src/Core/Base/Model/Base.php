<?php

Namespace Model;

class Base {

    public $params ;

    public $autopilotDefiner ;
    public $programNameFriendly;
    public $programNameInstaller;

    protected $installUserName;
    protected $installUserHomeDir;

    protected $programNameMachine ;
    protected $programDataFolder;
    protected $startDirectory;
    protected $titleData;
    protected $completionData;
    protected $bootStrapData;
    protected $extraBootStrap;
    protected $programExecutorFolder;
    protected $programExecutorTargetPath;
    protected $tempDir;
    protected $statusCommand;
    protected $statusCommandExpects;
    protected $versionInstalledCommand;
    protected $versionRecommendedCommand;
    protected $versionLatestCommand;
    protected $baseTempDir ;

    public function __construct($params) {
        $this->tempDir =  DIRECTORY_SEPARATOR.'tmp';
        $this->baseTempDir =  $this->tempDir ;
        $this->autopilotDefiner = $this->getModuleName() ;
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

    protected function populateTinyTitle() {
        $this->titleData = "$this->programNameInstaller Starting\n";
    }

    protected function populateTinyCompletion() {
        $this->completionData = "$this->programNameInstaller Complete\n";
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
        return $outputText;
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
            if (is_array($paramValue)) {
                // if the value is a php array, the param must be already formatted so do nothing
            }
            else if (substr($paramValue, 0, 2)=="--" && strpos($paramValue, '=') != null ) {
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

    // @todo update this method to use model factory
    protected function setInstallFlagStatus($bool) {
        if ($bool) {
            \Model\AppConfig::setProjectVariable("installed-modules", $this->getModuleName(), true); }
        else {
            \Model\AppConfig::deleteProjectVariable("installed-modules", "any", $this->programNameMachine); }
    }

    public function askStatus() {
        // @todo also use install flag status from methods setInstallFlagStatus getInstallFlagStatus
        if (isset($this->statusCommand) && !is_null($this->statusCommand) &&
            isset($this->statusCommandExpects) && !is_null($this->statusCommandExpects)) {
            $status = ($this->executeAndLoad("$this->statusCommand &") == $this->statusCommandExpects) ? true : false ; }
        else if (isset($this->statusCommand) && !is_null($this->statusCommand)) {
            $status = ($this->executeAndGetReturnCode("$this->statusCommand") == 0) ? true : false ; }
        else {
            $status = ($this->executeAndGetReturnCode("command -v $this->programNameMachine") == 0) ? true : false ; }
        return $status ;
    }

    protected function askStatusByArray($commsToCheck) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passing = true ;
        foreach ($commsToCheck as $commToCheck) {
            $outs = $this->executeAndLoad("command -v $commToCheck") ;
            if ( !strstr($outs, $commToCheck) ) {
                $logging->log("No command '{$commToCheck}' found") ;
                $passing = false ; }
            else {
                $logging->log("Command '{$commToCheck}' found") ; } }
        return $passing ;
    }

    // @todo fix this to use the model factory
    protected function getInstallFlagStatus($programNameMachine) {
        $installedApps = AppConfig::getProjectVariable("installed-modules");
        if (is_array($installedApps) && in_array($programNameMachine, $installedApps)) {
            return true ; }
        return false ;
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

    protected function askWhetherToDoAction($action) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( isset($this->actionsToMethods)) {
            if (isset($this->actionsToMethods[$action]) && method_exists($this, $this->actionsToMethods[$action])) {
                $return = $this->{$this->actionsToMethods[$action]}() ;
                return $return ; }
            else {
                $logging->log("No method {$this->actionsToMethods[$action]} in model ".get_class($this)) ;
                return false; } }
        else {
            $logging->log('No property $actionsToMethods in model '.get_class($this)) ;
            return false; }
    }

    public function getModuleName() {
        $reflector = new \ReflectionClass(get_class($this));
        $fileName = $reflector->getFileName();
        $end = strpos($fileName, DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR) ;
        $beforeModel = substr($fileName, 0, $end) ;
        $start = strrpos($beforeModel, DIRECTORY_SEPARATOR) ;
        $moduleName = substr($beforeModel, $start+1) ;
        return $moduleName ;
    }

    public function packageAdd($packager, $package) {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageEnsure($packager, $package, $this);
    }

    public function packageRemove($packager, $package) {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageRemove($packager, $package, $this);
    }

    /*Versioning starts here*/

    public function findVersion() {
        if (isset($this->params["version-type"])) {
            if (in_array($this->params["version-type"], array("Installed", "installed", "Recommended", "recommended", "Latest", "latest"))){
                return $this->getVersion($this->params["version-type"]); }
            else {
                return "Wrong Version Type"; } }
        else {
           return $this->getVersion(); }
    }

    public function getVersion($type = "Installed") {
        if (in_array($type, array("Installed", "installed", "Recommended", "recommended", "Latest", "latest"))) {
            $type = ucfirst($type) ;
            $property = "version{$type}Command" ;
            $trimmer = "{$property}Trimmer" ;
            if (isset($this->$property) && method_exists($this, $trimmer)) {
                $out = $this->executeAndLoad($this->$property);
                return new SoftwareVersion($this->$trimmer($out)) ; }
            else if (isset($this->$property)) {
                return $this->executeAndLoad($this->$property); }
            else {
                return false; } }
        else {
            return false; }
    }

    public function getVersionsAvailable() {
        if (isset($this->versionsAvailable)) {
            return $this->versionsAvailable ; }
        else if (method_exists($this, "versionsAvailable")) {
            return $this->versionsAvailable() ; }
        else {
            return false; }
    }

}
