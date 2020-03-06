<?php

Namespace Model;

class BaseLinuxApp extends Base {

    protected $installCommands;
    protected $uninstallCommands;
    protected $installUserName;
    protected $installUserHomeDir;
    protected $programExecutorCommand;
    public $defaultStatusCommandPrefix = "command -v";
    public $cur_progress ;

    public function __construct($params) {
        parent::__construct($params);
        $this->populateCompletion();
    }

    // @todo surely this can go its in every model nd basically pointless. DEPRECATE
    public function initialize() {
        $this->populateTitle();
    }

    public function askWhetherToInstallLinuxApp() {
        return $this->performLinuxAppInstall();
    }

    public function askInstall() {
        return $this->askWhetherToInstallLinuxApp();
    }

    public function askUnInstall() {
        return $this->askWhetherToUnInstallLinuxApp();
    }

    public function askWhetherToUnInstallLinuxApp() {
        return $this->performLinuxAppUnInstall();
    }

    protected function performLinuxAppInstall() {
        $doInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToInstallLinuxAppToScreen();
        if (!$doInstall) { return false; }
        return $this->install();
    }

    protected function performLinuxAppUnInstall() {
        $doUnInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
            true : $this->askWhetherToUnInstallLinuxAppToScreen();
        if (!$doUnInstall) { return false; }
        return $this->unInstall();
    }

    /*
     * Ensuring covers quite a lot,but it should follow this I think:
     * Most of it is to do with versions
     *
ensuring

if doing versions
	if its installed
		if installed is compatible
			do nothing * DONE (status 1)
		if installed is not compatible
            if recommended is compatible
                uninstall () * NOT DONE (status 2)
                install (recommended) * NOT DONE (status 2)
            if recommended is not compatible
                if latest version install is allowed
                    if latest is compatible
                        install (latest) * NOT DONE (status 3)
                    if latest is not compatible
                        exit (unable to meet installation requirements) * NOT DONE (status 4)
                if latest version install is not allowed
                    exit (unable to meet installation requirements) * NOT DONE (status 5)
	if not installed
		if recommended is compatible
			install (recommended) * DONE (status 6)
		if recommended is not compatible
			if latest version install is allowed
				if latest is compatible
					install (latest) * NOT DONE (status 7)
				if latest is not compatible
					exit (unable to meet installation requirements) * NOT DONE (status 8)
			if latest version install is not allowed
				exit (unable to meet installation requirements) * NOT DONE (status 9)
if not doing versions
	if its installed
		do nothing * DONE (status 10)
	if not installed
		install (default) * DONE (status 11)
     *
     */

    /*
     * @todo this method is 100 lines long, making it the one most in need of a refactor IMO
     * @
     */

    public function ensureInstalled(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["version"])) { // if checking versions
            $logging->log("Ensure install is checking versions", $this->getModuleName()) ;
            if ($this->askStatus() == true) {  // if already installed
                $logging->log("Already installed, checking version constraints", $this->getModuleName()) ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version operator is minimum",
                        $this->getModuleName()) ;
                    $this->params["version-operator"] = "+" ; }
                $currentVersion = $this->getVersion() ;
                if ($currentVersion !== false) {
                    $currentVersion->setCondition($this->params["version"], $this->params["version-operator"]) ; }
                if (is_object($currentVersion) && $currentVersion->isCompatible() == true) {
                    // status 1
                    $logging->log("Installed version {$currentVersion->shortVersionNumber} matches constraints, not installing",
                        $this->getModuleName()) ; }
                else {
                    $recVersion = $this->getVersion("Recommended") ;
                    if ($recVersion !== false) {
                        $recVersion->setCondition($this->params["version"], $this->params["version-operator"]) ; }
                    if (is_object($recVersion) && $recVersion->isCompatible() == true) {
                        // status 2
                        $logging->log(
                            "Installed version {$currentVersion->shortVersionNumber} does not match constraints, but ".
                            "Recommended version {$recVersion->shortVersionNumber} matches constraints, so installing".
                            " the old version and reinstalling the Recommended.", $this->getModuleName()) ;
                        $this->unInstall() ;
                        $this->install() ;  }
                    else {
                        $allowLatestVersion = (isset($this->params["allow-latest"])) ? $this->params["allow-latest"] : false ;
                        if ($allowLatestVersion == true) {
                            $latestVersion = $this->getVersion("Latest") ;
                            $latestVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                            if ($latestVersion->isCompatible()) {
                                // status 3
                                $logging->log(
                                    "Installed version {$currentVersion->shortVersionNumber} does not match constraints, but ".
                                    "Latest version {$latestVersion->shortVersionNumber} matches constraints, and use of Latest".
                                    " version is allowed so installing the old version and reinstalling the Latest.", $this->getModuleName()) ;
                                $this->unInstall() ;
                                // @todo this functionality doesnt work yet, but it should do this
                                $this->install("Latest") ;  }
                            else {
//                                var_dump($currentVersion, $latestVersion);
                                // status 4
                                $logging->log(
                                    "Installed version {$currentVersion->shortVersionNumber} does not match constraints, nor does ".
                                    "Recommended version {$recVersion->shortVersionNumber} or Latest version {$latestVersion->shortVersionNumber}.".
                                    " ... No More options! Cannot Continue.", $this->getModuleName()) ;
                                \Core\BootStrap::setExitCode(1) ; } }
                        else {
                            // status 5
                            $logging->log(
                                "Installed version {$currentVersion->shortVersionNumber} does not match constraints, nor does ".
                                "Recommended version {$recVersion->shortVersionNumber}. Latest versions are not allowed".
                                " ... No More options! Cannot Continue.", $this->getModuleName()) ;
                            \Core\BootStrap::setExitCode(1) ; } } } }
            else { // if not already installed
                $logging->log("Not already installed, checking version constraints", $this->getModuleName()) ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version is minimum", $this->getModuleName()) ;
                    $this->params["version-operator"] = "+" ; }
                $recVersion = $this->getVersion("Recommended") ;
                $recVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($recVersion->isCompatible()) {
                    // status 6
                    $logging->log("Recommended version {$recVersion->shortVersionNumber} matches constraints, installing",
                        $this->getModuleName()) ;
                    $this->install() ;  }
                else {

                    $allowLatestVersion = (isset($this->params["allow-latest"])) ? $this->params["allow-latest"] : false ;
                    if ($allowLatestVersion == true) {
                        $latestVersion = $this->getVersion("Latest") ;
                        $latestVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                        if ($latestVersion->isCompatible()) {
                            // status 7
                            $logging->log(
                                "Recommended version {$recVersion->shortVersionNumber} does not match constraints, but ".
                                "Latest version {$latestVersion->shortVersionNumber} matches constraints, and use of Latest".
                                " version is allowed so installing the old version and reinstalling the Latest.",
                                $this->getModuleName()) ;
                            $this->unInstall() ;
                            // @todo this functionality doesnt work yet, but it should do this
                            $this->install("Latest") ;  }
                        else {
                            // status 8
                            $logging->log(
                                "Recommended version {$recVersion->shortVersionNumber} does not match constraints, nor ".
                                "does or Latest version {$latestVersion->shortVersionNumber}. ... No More options! " .
                                "Cannot Continue.", $this->getModuleName()) ;
                            \Core\BootStrap::setExitCode(1) ; } }
                    else {
                        // status 9
                        $logging->log(
                            "Recommended version {$recVersion->shortVersionNumber} does not match constraints. Latest " .
                            "versions are not allowed ... No More options! Cannot Continue.", $this->getModuleName()) ;
                        \Core\BootStrap::setExitCode(1) ; } } } }
        else { // not checking version
            $logging->log("Ensure install is not checking versions", $this->getModuleName()) ;
            if ($this->askStatus() == true) {
                // status 10
                $logging->log("Not installing as already installed", $this->getModuleName()) ; }
            else {
                // status 11
                $logging->log("Installing as not installed", $this->getModuleName()) ;
                $this->install(); } }
        return true;
    }

    public function install() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
        $this->showTitle();
        $dic = $this->doInstallCommand() ;
        if ($dic === false) {
            $logging->log("Install steps failed", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        if ($this->programDataFolder) {
            $this->changePermissions($this->programDataFolder); }
        // $this->setInstallFlagStatus(true) ; @todo we can deprecate this now as status is dynamic, and install is used by everything not just installers
        if (isset($this->params["hide-completion"])) { $this->populateTinyCompletion(); }
        $this->showCompletion();
        return true ;
        // @todo should probably return askStatus
        // return $this->askStatus();
    }

    public function unInstall() {
        $this->showTitle();
        $this->doUnInstallCommand();
        // $this->setInstallFlagStatus(false) ; @todo we can deprecate this now as status is dynamic, and install is used by everything not just installers
        $this->showCompletion();
        return true;
    }

    public function runAtReboots() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->rebootsCommand)) {
            if (!is_array($this->rebootsCommand)) { $this->rebootsCommand = array($this->rebootsCommand) ; }
            $serviceFactory = new Service();
            $serviceManager = $serviceFactory->getModel($this->params) ;
            foreach ($this->rebootsCommand as $rebootsCommand) {
                $logging->log("Ensuring {$rebootsCommand} Will Run at Reboots", $this->getModuleName()) ;
                $serviceManager->setService($rebootsCommand);
                $serviceManager->runAtReboots(); } }
        else {
            $logging->log("This module does not report any services which can run at reboots", $this->getModuleName()) ; }
    }

    protected function showTitle() {
        print $this->titleData ;
    }

    protected function showCompletion() {
        print $this->completionData ;
    }

    protected function askWhetherToInstallLinuxAppToScreen(){
        $question = "Install ".$this->programNameInstaller."?";
        return self::askYesOrNo($question);
    }

    protected function askWhetherToUnInstallLinuxAppToScreen(){
        $question = "Uninstall ".$this->programNameInstaller."?";
        return self::askYesOrNo($question);
    }

    protected function askForInstallUserName() {
        $question = "Enter User To Install As:";
        $input = (isset($this->params["install-user-name"])) ? $this->params["install-user-name"] : self::askForInput($question);
        $this->installUserName = $input;
    }

    protected function askForInstallUserHomeDir() {
        $question = "Enter Install User Home Dir:";
        $input = (isset($this->params["install-user-home"])) ? $this->params["install-user-home"] : self::askForInput($question);
        $this->installUserHomeDir = $input ;
    }

    protected function askForInstallDirectory(){
        $question = "Enter Install Directory:";
        $input = (isset($this->params["install-directory"])) ? $this->params["install-directory"] : self::askForInput($question);
        $this->programDataFolder = $input;
    }

    protected function doInstallCommand(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (method_exists($this, "setInstallCommands")) { $this->setInstallCommands() ; }
        $this->swapCommandArrayPlaceHolders($this->installCommands);
        # var_dump('BaseLinuxApp') ;
        $i = 1 ;
        foreach ($this->installCommands as $installCommand) {
            # var_dump('bla do inst command current count is:', $i) ;
            $i++ ;
            $res = "" ;
            if ( array_key_exists("method", $installCommand)) {
                # var_dump('bla before cufa', get_class($installCommand["method"]["object"]), $installCommand["method"]["method"], $installCommand["method"]["params"]);
                $res = call_user_func_array(array($installCommand["method"]["object"], $installCommand["method"]["method"]), $installCommand["method"]["params"]);
                # var_dump('bla res') ;
            }
            else if ( array_key_exists("command", $installCommand)) {
                if (!is_array($installCommand["command"])) { $installCommand["command"] = array($installCommand["command"]); }
                $this->swapCommandArrayPlaceHolders($installCommand["command"]) ;
                foreach ($installCommand["command"] as $command) {
                    // var_dump("cmm", $installCommand);
                    $logging->log("Executing Install Command: {$command}", $this->getModuleName()) ;
                    $rc = self::executeAndGetReturnCode($command, true, true);
                    if ($rc["rc"] !== 0) {
                        $res = false ;
                        break ; } } }
            # var_dump('dumps');
            if ($res === false) {
                $logging->log("Failed Install Step", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        return true ;
    }

    protected function doUnInstallCommand(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (method_exists($this, "setUninstallCommands")) { $this->setUninstallCommands() ; }
        $this->swapCommandArrayPlaceHolders($this->uninstallCommands);
        foreach ($this->uninstallCommands as $uninstallCommand) {
            $res = "" ;
            if ( array_key_exists("method", $uninstallCommand)) {
                $res = call_user_func_array(array($uninstallCommand["method"]["object"], $uninstallCommand["method"]["method"]), $uninstallCommand["method"]["params"]); }
            else if ( array_key_exists("command", $uninstallCommand)) {
                if (!is_array($uninstallCommand["command"])) { $uninstallCommand["command"] = array($uninstallCommand["command"]); }
                $this->swapCommandArrayPlaceHolders($uninstallCommand["command"]) ;
                $res = self::executeAsShell($uninstallCommand["command"]) ; }
            if ($res === false) {
                $logging->log("Failed Uninstall Step", $this->getModuleName()) ;
                \Core\BootStrap::setExitCode(1) ;
                return false ; } }
        return true ;
    }

    protected function changePermissions($autoPilot, $target=null){
        if ($target != null) {
            $command = "chmod -R 775 $target";
            self::executeAndOutput($command); }
    }

    protected function deleteExecutorIfExists(){
        $command = 'rm -f '.$this->programExecutorFolder.DS.$this->programNameMachine;
        self::executeAndOutput($command, "Program Executor Deleted if existed");
        return true;
    }

    protected function saveExecutorFile(){
        $this->populateExecutorFile();
        $executorPath = $this->programExecutorFolder.DS.$this->programNameMachine;
        file_put_contents($executorPath, $this->bootStrapData);
        $this->changePermissions(null, $executorPath);
    }

    protected function populateExecutorFile() {
        $this->bootStrapData = "#!/usr/bin/php\n
<?php\n
exec('".$this->programExecutorCommand."');\n
?>";
    }

    public function getPackageSearchString() {
        if (isset($this->packageSearchString)) {
            return $this->packageSearchString ;
        } else {
            return $this->programNameInstaller ;
        }
    }


    public function packageDownload($remote_source, $temp_exe_file) {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Downloading From {$remote_source}", $this->getModuleName() ) ;
        $logging->log("Downloading To {$temp_exe_file}", $this->getModuleName() ) ;
        if (file_exists($temp_exe_file)) {
            if (isset($this->params['skip-existing'])) {
                $logging->log("File {$temp_exe_file} exists and skip-existing parameter is set, skipping this download.", $this->getModuleName() ) ;
                return true ;
            } elseif (isset($this->params['overwrite'])) {
                $logging->log("File {$temp_exe_file} exists and overwrite parameter is set, removing.", $this->getModuleName() ) ;
                unlink($temp_exe_file) ;
            } else {
                $logging->log("File {$temp_exe_file} exists and overwrite parameter is unset.", $this->getModuleName(), LOG_FAILURE_EXIT_CODE ) ;
                return false ;
            }
        }
        if (function_exists('curl_version')) {

            echo "Download Starting ...".PHP_EOL;
            ob_clean();
            ob_end_clean();
            ob_start();
            $fp = fopen ($temp_exe_file, 'w') ;
            $ch = \curl_init();
            \curl_setopt($ch, CURLOPT_URL, $remote_source);
            // curl_setopt($ch, CURLOPT_BUFFERSIZE,128);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            \curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            \curl_setopt($ch, CURLOPT_FILE, $fp);
            \curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, 'progress'));
            \curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
            \curl_setopt($ch, CURLOPT_HEADER, 0);
            \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_exec($ch);
            # $error = curl_error($ch) ;
            # var_dump('downloaded', $downloaded, $error) ;
            \curl_close($ch);

//            ob_flush();
//            flush();
            ob_clean();
            ob_end_clean();

        } else {
            echo "No PHP cURL Available".PHP_EOL;
            echo "Fallback Download Starting ...".PHP_EOL;
            $allowed = ini_get('allow_url_fopen') ;
            if ($allowed === true) {
                echo "Using FOpen Fallback".PHP_EOL;
//                $file_data = file_get_contents($remote_source) ;
//                file_put_contents($temp_exe_file, $file_data) ;
            } else {
                echo "Using CLI Fallback".PHP_EOL;
                $comm = "wget --progress=dot:giga -O $temp_exe_file $remote_source" ;
                echo "$comm".PHP_EOL;
//                passthru($comm, $return_var) ;
            }
        }

        echo "Done".PHP_EOL ;
        return $temp_exe_file ;
    }

    public function progress($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        $is_noprogress = (isset($this->params['noprogress']) ) ? true : false ;
        if ($is_noprogress == false) {
            if($download_size > 0) {
                $dl = ($downloaded / $download_size)  * 100 ;
                # var_dump('downloaded', $dl) ;
                $perc = round($dl, 2) ;
                # var_dump('perc', $perc) ;
                echo "{$perc} % \r" ;
            }
        } else {
            if($download_size > 0) {
                $dl = ($downloaded / $download_size)  * 100 ;
                # var_dump('downloaded', $dl) ;
                $perc = round($dl) ;
                # var_dump('perc', $perc) ;

                if ($perc !== $this->cur_progress) {
                    echo "{$perc} %  \r\n" ;
                    $this->cur_progress = $perc ;
                }

            }
        }
        ob_flush();
        flush();
    }

}