<?php

Namespace Model;

class BaseLinuxApp extends Base {

    protected $installCommands;
    protected $uninstallCommands;
    protected $installUserName;
    protected $installUserHomeDir;
    protected $programExecutorCommand;

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
     */

    public function ensureInstalled(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["version"])) { // if checking versions
            $logging->log("Ensure install is checking versions") ;
            if ($this->askStatus() == true) {  // if already installed
                $logging->log("Already installed, checking version constraints") ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version operator is minimum") ;
                    $this->params["version-operator"] = "+" ; }
                $currentVersion = $this->getVersion() ;
                $currentVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($currentVersion->isCompatible() == true) {
                    // status 1
                    $logging->log("Installed version {$currentVersion->shortVersionNumber} matches constraints, not installing") ; }
                else {
                    $recVersion = $this->getVersion("Recommended") ;
                    $recVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                    if ($recVersion->isCompatible()) {
                        // status 2
                        $logging->log(
                            "Installed version {$currentVersion->shortVersionNumber} does not match constraints, but ".
                            "Recommended version {$recVersion->shortVersionNumber} matches constraints, so installing".
                            " the old version and reinstalling the Recommended.") ;
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
                                    " version is allowed so installing the old version and reinstalling the Latest.") ;
                                $this->unInstall() ;
                                // @todo this functionality doesnt work yet, but it should do this
                                $this->install("Latest") ;  }
                            else {
                                // status 4
                                $logging->log(
                                    "Installed version {$currentVersion->shortVersionNumber} does not match constraints, nor does ".
                                    "Recommended version {$recVersion->shortVersionNumber} or Latest version {$latestVersion->shortVersionNumber}.".
                                    " ... No More options! Cannot Continue.") ;
                                \Core\BootStrap::setExitCode(1) ; } }
                        else {
                            // status 5
                            $logging->log(
                                "Installed version {$currentVersion->shortVersionNumber} does not match constraints, nor does ".
                                "Recommended version {$recVersion->shortVersionNumber}. Latest versions are not allowed".
                                " ... No More options! Cannot Continue.") ;
                            \Core\BootStrap::setExitCode(1) ; } } } }
            else { // if not already installed
                $logging->log("Not already installed, checking version constraints") ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version is minimum") ;
                    $this->params["version-operator"] = "+" ; }
                $recVersion = $this->getVersion("Recommended") ;
                $recVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($recVersion->isCompatible()) {
                    // status 6
                    $logging->log("Recommended version {$recVersion->shortVersionNumber} matches constraints, installing") ;
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
                                " version is allowed so installing the old version and reinstalling the Latest.") ;
                            $this->unInstall() ;
                            // @todo this functionality doesnt work yet, but it should do this
                            $this->install("Latest") ;  }
                        else {
                            // status 8
                            $logging->log(
                                "Recommended version {$recVersion->shortVersionNumber} does not match constraints, nor ".
                                "does or Latest version {$latestVersion->shortVersionNumber}. ... No More options! " .
                                "Cannot Continue.") ;
                            \Core\BootStrap::setExitCode(1) ; } }
                    else {
                        // status 9
                        $logging->log(
                            "Recommended version {$recVersion->shortVersionNumber} does not match constraints. Latest " .
                            "versions are not allowed ... No More options! Cannot Continue.") ;
                        \Core\BootStrap::setExitCode(1) ; } } } }
        else { // not checking version
            $logging->log("Ensure module install is not checking versions") ;
            if ($this->askStatus() == true) {
                // status 10
                $logging->log("Not installing as already installed") ; }
            else {
                // status 11
                $logging->log("Installing as not installed") ;
                $this->install(); } }
        return true;
    }

    public function install() {
        if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
        $this->showTitle();
        $this->doInstallCommand();
        if ($this->programDataFolder) {
            $this->changePermissions($this->programDataFolder); }
        // $this->setInstallFlagStatus(true) ; @todo we can deprecate this now as status is dynamic, and install is used by everything not just installers
        if (isset($this->params["hide-completion"])) { $this->populateTinyCompletion(); }
        $this->showCompletion();
        return true;
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
        if (isset($this->serviceCommand)) {
            if (!is_array($this->serviceCommand)) { $this->serviceCommand = array($this->serviceCommand) ; }
            $serviceFactory = new Service();
            $serviceManager = $serviceFactory->getModel($this->params) ;
            foreach ($this->serviceCommand as $serviceCommand) {
                $logging->log("Ensuring {$serviceCommand} Will Run at Reboots") ;
                $serviceManager->setService($serviceCommand);
                $serviceManager->runAtReboots(); } }
        else {
            $logging->log("This module does not report any services which can run at reboots") ; }
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
        self::swapCommandArrayPlaceHolders($this->installCommands);
        foreach ($this->installCommands as $installCommand) {
            if ( array_key_exists("method", $installCommand)) {
                call_user_func_array(array($installCommand["method"]["object"], $installCommand["method"]["method"]), $installCommand["method"]["params"]); }
            else if ( array_key_exists("command", $installCommand)) {
                if (!is_array($installCommand["command"])) { $installCommand["command"] = array($installCommand["command"]); }
                $this->swapCommandArrayPlaceHolders($installCommand["command"]) ;
                self::executeAsShell($installCommand["command"]) ; } }
    }

    protected function doUnInstallCommand(){
        self::swapCommandArrayPlaceHolders($this->uninstallCommands);
        foreach ($this->uninstallCommands as $uninstallCommand) {
            if ( array_key_exists("method", $uninstallCommand)) {
                call_user_func_array(array($uninstallCommand["method"]["object"], $uninstallCommand["method"]["method"]), $uninstallCommand["method"]["params"]); }
            else if ( array_key_exists("command", $uninstallCommand)) {
                if (!is_array($uninstallCommand["command"])) { $uninstallCommand["command"] = array($uninstallCommand["command"]); }
                $this->swapCommandArrayPlaceHolders($uninstallCommand["command"]) ;
                self::executeAsShell($uninstallCommand["command"]) ; } }
    }

    protected function changePermissions($autoPilot, $target=null){
        if ($target != null) {
            $command = "chmod -R 775 $target";
            self::executeAndOutput($command); }
    }

    protected function deleteExecutorIfExists(){
        $command = 'rm -f '.$this->programExecutorFolder.DIRECTORY_SEPARATOR.$this->programNameMachine;
        self::executeAndOutput($command, "Program Executor Deleted if existed");
        return true;
    }

    protected function saveExecutorFile(){
        $this->populateExecutorFile();
        $executorPath = $this->programExecutorFolder.DIRECTORY_SEPARATOR.$this->programNameMachine;
        file_put_contents($executorPath, $this->bootStrapData);
        $this->changePermissions(null, $executorPath);
    }

    protected function populateExecutorFile() {
      $this->bootStrapData = "#!/usr/bin/php\n
<?php\n
exec('".$this->programExecutorCommand."');\n
?>";
    }

}