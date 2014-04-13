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

    public function ensureInstalled(){
        $consoleFactory = new \Model\Console();
        $console = $consoleFactory->getModel($this->params);
        if ($this->askStatus() == true) {
            $console->log("Not installing as already installed") ; }
        else {
            $console->log("Installing as not installed") ;
            $this->install(); }
        return true;
    }

    public function install() {
        if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
        $this->showTitle();
        $this->doInstallCommand();
        if ($this->programDataFolder) {
            $this->changePermissions($this->programDataFolder); }
        $this->setInstallFlagStatus(true) ;
        if (isset($this->params["hide-completion"])) { $this->populateTinyCompletion(); }
        $this->showCompletion();
        return true;
    }

    public function unInstall() {
        $this->showTitle();
        $this->doUnInstallCommand();
        $this->setInstallFlagStatus(false) ;
        $this->showCompletion();
        return true;
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