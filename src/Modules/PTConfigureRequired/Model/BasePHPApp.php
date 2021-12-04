<?php

Namespace Model;

class BasePHPApp extends Base {

    protected $fileSources; // array ("repo url", "custom dir", "custom branch", "single depth")
    protected $preinstallCommands;
    protected $preuninstallCommands;
    protected $postinstallCommands;
    protected $postuninstallCommands;
    protected $executorPath ;
    protected $tempFileStore ;

    public function __construct($params) {
        parent::__construct($params);
        $this->findExecutorPath();
        $this->populateStartDirectory();
        $this->populateCompletion();
    }

    public function initialize() {
        $this->populateTitle();
        $this->versionInstalledCommand = $this->executorPath." --git-dir=".PFILESDIR."{$this->programNameMachine}".DS."{$this->programNameMachine}".DS.".git --work-tree=".DS."{$this->programNameMachine} tag" ;
        $this->versionRecommendedCommand = $this->executorPath." --git-dir=".PFILESDIR."{$this->programNameMachine}".DS."{$this->programNameMachine}".DS.".git --work-tree=".DS."{$this->programNameMachine} tag" ;
        $this->versionLatestCommand = $this->executorPath." --git-dir=".PFILESDIR."{$this->programNameMachine}".DS."{$this->programNameMachine}".DS.".git --work-tree=".DS."{$this->programNameMachine} tag" ;
    }

    protected function findExecutorPath() {
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            $file_orig_pfiles = "{$_ENV['ProgramFiles']}\\Git\\bin\\" ;
            $file_new_pfiles = "{$_ENV['ProgramW6432']}\\Git\\bin\\" ;
            if (file_exists($file_orig_pfiles)) {
                $this->executorPath = '"'.$file_orig_pfiles.'git.exe" ' ;
            } else if (file_exists($file_new_pfiles)) {
                $this->executorPath = '"'.$file_new_pfiles.'git.exe" ' ;
            } else {
                $this->executorPath = 'git.exe ' ;
            } }
        else {
            $this->executorPath = "/usr/bin/git " ; }
    }

    protected function populateStartDirectory() {
        $this->startDirectory = str_replace(DS."$this->programNameMachine", "", self::$tempDir);
    }

    public function askWhetherToInstallPHPApp() {
        return $this->performPHPAppInstall();
    }

    public function askInstall() {
        return $this->askWhetherToInstallPHPApp();
    }

    public function askUnInstall() {
        return $this->askWhetherToUninstallPHPApp();
    }

    public function askWhetherToUninstallPHPApp() {
        return $this->performPHPAppUnInstall();
    }

    protected function performPHPAppInstall() {
        $doInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToInstallPHPAppToScreen();
        if (!$doInstall) { return false; }
        return $this->install();
    }

    protected function performPHPAppUnInstall() {
        $doUnInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToUnInstallPHPAppToScreen();
        if (!$doUnInstall) { return false; }
        return $this->unInstall();
    }

    public function ensureInstalled(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["version"])) {
            $logging->log("Ensure install is checking versions", $this->getModuleName()) ;
            if ($this->askStatus() == true) {
                $logging->log("Already installed, checking version constraints", $this->getModuleName()) ;
                $this->transformLatestParameter() ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version operator is minimum", $this->getModuleName()) ;
                    $this->params["version-operator"] = "+" ; }
                $currentVersion = $this->getVersion("Installed") ;
                $currentVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($currentVersion->isCompatible() == true) {
                    $vn = (strlen($currentVersion->shortVersionNumber)>0) ? $currentVersion->shortVersionNumber : $currentVersion->fullVersionNumber ;
                    $logging->log("Installed version {$vn} matches constraints, nothing to do", $this->getModuleName()) ;
                    return true ; }
                else {
                    // @todo check if requested version is available
                    $vn = (strlen($currentVersion->shortVersionNumber)>0) ? $currentVersion->shortVersionNumber : $currentVersion->fullVersionNumber ;
                    $logging->log("Installed version {$vn} does not match constraint, updating", $this->getModuleName()) ;
                    return $this->doGitCheckout() ;
                    /* @todo do a proper uninstall and install right version */ } }
            else {
                $logging->log("Not already installed, checking version constraints", $this->getModuleName()) ;
                $this->transformLatestParameter() ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version is minimum", $this->getModuleName()) ;
                    $this->params["version-operator"] = "+" ; }
                $recVersion = $this->getVersion("Recommended") ;
                $recVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($recVersion->isCompatible()) {
                    $vn = (strlen($recVersion->shortVersionNumber)>0) ? $recVersion->shortVersionNumber : $recVersion->fullVersionNumber ;
                    $logging->log("Requested version {$vn} matches constraints, installing", $this->getModuleName()) ;
                    return $this->install() ;  }
                else {
                    $vn = (strlen($recVersion->shortVersionNumber)>0) ? $recVersion->shortVersionNumber : $recVersion->fullVersionNumber ;
                    $logging->log("Requested version {$vn} does not match constraint, unable to continue", $this->getModuleName()) ;
                    return false ; } } }
        else { // not checking version
            $logging->log("Ensure install is not checking versions", $this->getModuleName()) ;
            if ($this->askStatus() == true) {
                $logging->log("Not installing as already installed", $this->getModuleName()) ;
                return true ; }
            else {
                $logging->log("Installing as not installed", $this->getModuleName()) ;
                return $this->install() ; } }
        return true;
    }

    public function transformLatestParameter() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( (isset($this->params["version"]) && $this->params["version"] == "latest") ||
            (isset($this->params["version-type"]) && $this->params["version-type"] == "latest") ) {
            $this->params["version-type"] = "latest" ;
            $this->params["version-operator"] = '=' ;
            $logging->log("Requested version is latest, calculating", $this->getModuleName()) ;
            $latestVersion = $this->getVersion("Latest") ;
            if (!is_null($latestVersion) && $latestVersion != false && $latestVersion != "") {
                $logging->log("Found latest version {$latestVersion->fullVersionNumber}", $this->getModuleName()) ;
                $logging->log("Setting latest version to {$latestVersion->fullVersionNumber}", $this->getModuleName()) ;
                $this->params["version"] = $latestVersion->fullVersionNumber ; }
            else {
                $logging->log("Unable to find latest version", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ; } }
    }

    public function install() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
        $this->showTitle();
        if ($this->hookInstExists("pre")) {
            $logging->log("Executing Pre Install Commands", $this->getModuleName()) ;
            $this->doInstallCommand("pre") ; }
        $this->programDataFolder = $this->askForProgramDataFolder();
        if ( !isset($this->params['no-clone']) ) {
            $this->programExecutorFolder = $this->askForProgramExecutorFolder();
            if (isset($this->force_code_dir)) {
                if ($this->deleteProgramDataFolderAsRootIfExists($this->force_code_dir) === false) { return false ; }
            } else {
                if ($this->deleteProgramDataFolderAsRootIfExists() === false) { return false ; }
            }
            if ($this->makeProgramDataFolderIfNeeded() === false) { return false ; }
            if ($this->doGitClone() === false) { return false ; }
            if ($this->copyFilesToProgramDataFolder() === false) { return false ; }
        }
        $de = $this->deleteExecutorIfExists() ;
        if ($de === false) { return false ; }
        if ($this->populateExecutorFile() === false) { return false ; }
        if ($this->saveExecutorFile() === false) { return false ; }
        if ($this->changeExecutorPermissions() === false) { return false; }
        if ( !isset($this->params['no-clone']) ) {
            if ($this->deleteInstallationFiles() === false) {
                return false;
            }
            if (!isset($this->params["no-permissions-change"])) {
                if ($this->changeSourcePermissions() === false) {
                    return false;
                }
            }
        }
        if ($this->hookInstExists("post")) {
            $logging->log("Executing Post Install Commands", $this->getModuleName()) ;
            $this->doInstallCommand("post") ;  }
        if (isset($this->params["hide-completion"])) { $this->populateTinyCompletion(); }
        $this->showCompletion() ;
        return true ;
    }

    protected function hookInstExists($prepost) {
        $method = "set{$prepost}installCommands" ;
        if (method_exists($this, $method)) {
            return true ; }
        $ppc = "{$prepost}installCommands" ;
        if (isset($this->{$ppc}) &&
            is_array($this->{$ppc}) &&
            count($this->{$ppc})>0) {
            return true ; }
        return false ;
    }

    public function unInstall($autoPilot = null) {
        $this->showTitle();
        $this->programDataFolder = ($autoPilot)
            ? $autoPilot->{$this->autopilotDefiner}
            : $this->askForProgramDataFolder();
        $this->programExecutorFolder = $this->askForProgramExecutorFolder();
        if ($this->deleteProgramDataFolderAsRootIfExists() === false) { return false ; }
        if ($this->deleteExecutorIfExists() === false) { return false ; }
        if ($this->showCompletion() === false) { return false ; }
        return true ;
    }

    protected function showTitle() {
        print $this->titleData ;
    }

    protected function showCompletion() {
        print $this->completionData ;
    }

    protected function askWhetherToInstallPHPAppToScreen(){
        $question = "Install ".$this->programNameInstaller." ?";
        return self::askYesOrNo($question);
    }

    protected function askWhetherToUnInstallPHPAppToScreen(){
        $question = "Un Install ".$this->programNameInstaller." ?";
        return self::askYesOrNo($question);
    }

    protected function askForProgramDataFolder() {
        $default_dir =  PFILESDIR.$this->programNameMachine;
        if (isset($this->params["program-data-directory"])) { return $this->params["program-data-directory"] ; }
        $question = 'What is the program data directory?';
        $question .= ' Found "'.$default_dir.'" - use this? (Enter nothing for yes, no end slash)';
        $input = (isset($this->params["yes"]) && $this->params["yes"]==true) ? $default_dir : self::askForInput($question);
        return ($input=="") ? $default_dir : $input ;
    }

    protected function askForProgramExecutorFolder(){
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            $default_dir =  PFILESDIR; }
        else if (in_array(PHP_OS, array("Darwin"))) {
            $default_dir =  '/usr/local/bin/'; }
        else {
            $default_dir =  '/usr/bin/'; }
        $question = 'What is the program executor directory?';
        $question .= ' Found "'.$default_dir.'" - use this? (Enter nothing for yes, No Trailing Slash)';
        $input = (isset($this->params["yes"]) && $this->params["yes"]==true) ? $default_dir : self::askForInput($question);
        return ($input=="") ? $default_dir : $input ;
    }

    protected function populateExecutorFile() {
        if (isset($this->params["no-executor"])) { return true ; }
        $arrayOfPaths = scandir($this->programDataFolder);
        $pathStr = "" ;
        foreach ($arrayOfPaths as $path) {
            $pathStr .= $this->programDataFolder.DS.$path . PATH_SEPARATOR ; }
        $this->bootStrapData = "#!/usr/bin/php
<?php
set_include_path('" . $pathStr . "'.get_include_path() );
require('".$this->programDataFolder.DIRECTORY_SEPARATOR.$this->programExecutorTargetPath."');\n
?>";
        return true ;
    }

    protected function deleteProgramDataFolderAsRootIfExists($force_dir=null){
        $del_dir = ($force_dir==null) ? $this->programDataFolder : $force_dir ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Checking for existing data folder to delete {$del_dir}", $this->getModuleName()) ;
        if ( is_dir($del_dir)) {
            if (in_array(PHP_OS, array("Windows", "WINNT"))) {
                $del_comm =  'rd '.$del_dir.' /S /Q';
                $rc = self::executeAndGetReturnCode($del_comm, true, true);
            }
            else {
                $del_comm =  SUDOPREFIX.' rm -rf ';
                $command = $del_comm.$del_dir;
                $rc = self::executeAndGetReturnCode($command, true, true); }
            if ($rc["rc"] !== 0) {
                $logging->log("Error deleting data folder {$del_dir}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; }
            $logging->log("Data folder {$del_dir} deleted", $this->getModuleName()) ;
            return true;}
        $logging->log("No data folder to delete {$del_dir}", $this->getModuleName()) ;
        return true;
    }


    protected function makeProgramDataFolderIfNeeded(){
        if (!file_exists($this->programDataFolder)) {
            $res = mkdir($this->programDataFolder,  0777, true);
            return ($res==false) ? false : true ; }
        return true ;
    }

    protected function copyFilesToProgramDataFolder() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Preparing to copy to Program Data folder", $this->getModuleName()) ;
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            $copy_comm =  'xcopy /q /s /e /y '; }
        else {
            $copy_comm =  'cp -r '; }
        foreach ($this->fileSources as $fileSource) {
            $fs = "" ;
            if (isset($fileSource[3])&& $fileSource[3]==true) { $fs = DS.'.' ; }
            $command = $copy_comm.$this->tempFileStore.$fs.' '.$this->programDataFolder;
            $rc = self::executeAndGetReturnCode($command, true, true);
            if ($rc["rc"] !== 0) {
                $logging->log("Error copying to Program Data folder", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; }
            $logging->log("Program Data folder deleted", $this->getModuleName()) ; }
        return true;
    }

    protected function deleteExecutorIfExists(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Preparing to delete executor if it exists", $this->getModuleName()) ;
        if (file_exists($this->programExecutorFolder.DS.$this->programNameMachine)) {
            if (in_array(PHP_OS, array("Windows", "WINNT"))) {
                $del_comm =  'del /S /Q '; }
            else {
                $del_comm =  'rm -rf '; }
            $command = $del_comm.$this->programExecutorFolder.DS.$this->programNameMachine;
            $rc = self::executeAndGetReturnCode($command, true, true);
            if ($rc["rc"] !== 0) {
                $logging->log("Error Deleting Program Executor", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; }
            $logging->log("Program Executor deleted", $this->getModuleName()) ;
            return true; }
        else {
            $logging->log("No Program Executor To Delete", $this->getModuleName()) ;
            return true ;}
    }

    protected function deleteInstallationFiles(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Preparing to delete Installation files", $this->getModuleName()) ;
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            $del_comm =  'del /S /Q '; }
        else {
            $del_comm =  'rm -rf '; }
        $command = $del_comm.$this->tempFileStore;
        $rc = self::executeAndGetReturnCode($command, true, true);
        if ($rc["rc"] !== 0) {
            $logging->log("Error deleting installation files", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $logging->log("Installation files deleted", $this->getModuleName()) ;
        return true ;
    }

    protected function saveExecutorFile(){
        if (isset($this->params["no-executor"])) { return true ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Preparing to save executor file", $this->getModuleName()) ;
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            $file_ext = '.cmd' ; }
        else {
            $file_ext = '' ; }
        $this->populateExecutorFile();
        $pxf = $this->ensureTrailingSlash($this->programExecutorFolder);
        return file_put_contents($pxf.$this->programNameMachine.$file_ext, $this->bootStrapData);
    }

    protected function changeSourcePermissions(){
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            return true ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Preparing to change application file permissions", $this->getModuleName()) ;
        $command = "chmod -R +x $this->programDataFolder";
        $this->executeAndOutput($command);
        $rc = self::executeAndGetReturnCode($command, true, true);
        if ($rc["rc"] !== 0) {
            $logging->log("Error changing file permissions", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $logging->log("Changing application file permissions complete", $this->getModuleName()) ;
        return true ;
    }

    protected function changeExecutorPermissions(){
        if (in_array(PHP_OS, array("Windows", "WINNT"))) {
            return true ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["no-executor"])) { return true ; }
        $file_ext = '' ;
        $pxf = $this->ensureTrailingSlash($this->programExecutorFolder);
        $full_file = $pxf.$this->programNameMachine.$file_ext ;
        $command = "chmod +x {$full_file}";
        $rc = self::executeAndGetReturnCode($command, true, true);
        if ($rc["rc"] !== 0) {
            $logging->log("Error changing executor permissions", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $logging->log("Changing executor permissions complete for {$full_file}", $this->getModuleName()) ;
        return true ;
    }

    // keep method for BC
    protected function doGitCloneWithErrorCheck(){
        return $this->doGitClone();
    }

    protected function doGitClone(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        foreach ($this->fileSources as $fileSource) {
            $this->tempFileStore = BASE_TEMP_DIR.$this->programNameMachine ;
            if ($fileSource[1] != null) {
                $this->tempFileStore .= DIRECTORY_SEPARATOR.$fileSource[1] ; }
            $this->deleteProgramDataFolderAsRootIfExists($this->tempFileStore) ;
            $command  = $this->executorPath.' clone ';
            if (isset($fileSource[3]) &&
                $fileSource[3] = true) { $command .= '--recursive ';}
            if ($fileSource[2] != null) { $command .= '-b '.$fileSource[2].' ';}
            $command .= escapeshellarg($fileSource[0]).' ';
            $command .= ' '.$this->tempFileStore ;
            $logging->log("Git command is $command", $this->getModuleName()) ;

            $res = $this->runWithRetries($command);
            return $res ;}

        return true ;
    }

    protected function runWithRetries($command) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $passed = false ;
        for ($tried = 0; $tried < 3; $tried++) {
            $rc = self::executeAndGetReturnCode($command, true, true);
            $status = ($rc["rc"] !== 0) ? false : true ;
            if ($status == false) {
                sleep(2) ;
                continue ; }
            if ($status == true) {
                $passed = true ;
                break ; } }
        if ($passed == false) {
            $logging->log("Error performing Git command", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;  }
    }

    protected function doGitCheckout(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        foreach ($this->fileSources as $fileSource) {
            $this->tempFileStore = BASE_TEMP_DIR.$this->programNameMachine ;
            if ($fileSource[1] != null) {
                $this->tempFileStore .= DIRECTORY_SEPARATOR.$fileSource[1] ; }
            $this->deleteProgramDataFolderAsRootIfExists($this->tempFileStore) ;
            $command  = $this->executorPath.' --git-dir '.$this->getModuleDirectory().DS.".git".DS.' reset --hard ' ;
            $logging->log("Git command is $command", $this->getModuleName()) ;
            $res = $this->runWithRetries($command);
            if ($res == false) {
                $logging->log("Error performing Git command", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; }
            $command  = $this->executorPath.' --git-dir '.$this->getModuleDirectory().DS.".git".DS.' pull origin master ' ;
            $logging->log("Git command is $command", $this->getModuleName()) ;
            $res = $this->runWithRetries($command);
            if ($res == false) {
                $logging->log("Error performing Git command", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; }
            $command  = $this->executorPath.' --git-dir '.$this->getModuleDirectory().DS.".git".DS.' checkout master' ;
            $logging->log("Git command is $command", $this->getModuleName()) ;
            $res = $this->runWithRetries($command);
            if ($res == false) {
                $logging->log("Error performing Git command", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; }
            $command  = $this->executorPath.' --git-dir '.$this->getModuleDirectory().DS.".git".DS." checkout ".$this->params["version"];
            $logging->log("Git command is $command", $this->getModuleName()) ;
            $res = $this->runWithRetries($command);
            if ($res == false) {
                $logging->log("Error performing Git command", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        return true ;
    }


    public function versionInstalledCommandTrimmer($text) {
        $lastNewLine = strrpos($text, "\n") ;
        $penultimateNewLine = strrpos(substr($text, 0, $lastNewLine), "\n") ;
        $done = substr($text, $penultimateNewLine, $lastNewLine) ;
        $done = trim($done, "\n") ;
        $done = trim($done, "\r") ;
        return $done ;
    }

    // @todo this should check the repo or do git pull, etc
    public function versionLatestCommandTrimmer($text) {
        $lastNewLine = strrpos($text, "\n") ;
        $penultimateNewLine = strrpos(substr($text, 0, $lastNewLine), "\n") ;
        $done = substr($text, $penultimateNewLine, $lastNewLine) ;
        $done = trim($done, "\n") ;
        $done = trim($done, "\r") ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $lastNewLine = strrpos($text, "\n") ;
        $penultimateNewLine = strrpos(substr($text, 0, $lastNewLine), "\n") ;
        $done = substr($text, $penultimateNewLine, $lastNewLine) ;
        $done = trim($done, "\n") ;
        $done = trim($done, "\r") ;
        return $done ;
    }

    protected function doInstallCommand($hook) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $property = "{$hook}installCommands";
        $method = "set{$hook}installCommands";
//        var_dump($method) ;
        if (method_exists($this, $method)) {
            $this->$method();
        }
        $this->swapCommandArrayPlaceHolders($this->$property);
        foreach ($this->$property as $installCommand) {
            $res = "";
            if (array_key_exists("method", $installCommand)) {
                $res = call_user_func_array(array($installCommand["method"]["object"], $installCommand["method"]["method"]), $installCommand["method"]["params"]);
            } else if (array_key_exists("command", $installCommand)) {
                if (!is_array($installCommand["command"])) {
                    $installCommand["command"] = array($installCommand["command"]);
                }
                $this->swapCommandArrayPlaceHolders($installCommand["command"]);
                $return_var = false ;
                foreach ($installCommand["command"] as $command) {
                    passthru($command, $return_var);
                    if ($return_var !== 0) {
                        break ;
                    }
                }
                if ($return_var === 0) {
                    $res = true ;
                } else {
                    $res = false ; } }
            if ($res === false) {
                $logging->log("Failed Uninstall Step", $this->getModuleName());
                \Core\BootStrap::setExitCode(1);
                break; } }
    }

    protected function doUnInstallCommand($hook){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $property = "{$hook}uninstallCommands" ;
        $method = "set{$hook}uninstallCommands" ;
//        var_dump($method) ;
        if (method_exists($this, $method)) { $this->$method() ; }
        $this->swapCommandArrayPlaceHolders($this->$property);
        foreach ($this->$property as $uninstallCommand) {
            $res = "" ;
            if ( array_key_exists("method", $uninstallCommand)) {
                $res = call_user_func_array(array($uninstallCommand["method"]["object"], $uninstallCommand["method"]["method"]), $uninstallCommand["method"]["params"]); }
            else if ( array_key_exists("command", $uninstallCommand)) {
                if (!is_array($uninstallCommand["command"])) { $uninstallCommand["command"] = array($uninstallCommand["command"]); }
                $this->swapCommandArrayPlaceHolders($uninstallCommand["command"]) ;
                $res =  $this->executeAsShell($uninstallCommand["command"]) ; }
            if ($res === false) {
                $logging->log("Failed Uninstall Step", $this->getModuleName()) ;
                \Core\BootStrap::setExitCode(1) ;
                break ; }  }
    }

}
