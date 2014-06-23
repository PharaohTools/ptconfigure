<?php

Namespace Model;

class BasePHPApp extends Base {

    protected $fileSources;

    public function __construct($params) {
        parent::__construct($params);
        $this->populateStartDirectory();
        $this->populateCompletion();
    }

    public function initialize() {
        $this->populateTitle();
        $this->versionInstalledCommand = "git --git-dir=/opt/{$this->programNameMachine}/{$this->programNameMachine}/.git --work-tree=/{$this->programNameMachine} tag" ;
        $this->versionRecommendedCommand = "git --git-dir=/opt/{$this->programNameMachine}/{$this->programNameMachine}/.git --work-tree=/{$this->programNameMachine} tag" ;
        $this->versionLatestCommand = "git --git-dir=/opt/{$this->programNameMachine}/{$this->programNameMachine}/.git --work-tree=/{$this->programNameMachine} tag" ;
    }

    protected function populateStartDirectory() {
        $this->startDirectory = str_replace("/$this->programNameMachine", "", $this->tempDir);
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
    $this->install();
    return true;
  }

  protected function performPHPAppUnInstall() {
    $doUnInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
      true : $this->askWhetherToUnInstallPHPAppToScreen();
    if (!$doUnInstall) { return false; }
    $this->unInstall();
    return true;
  }

    public function ensureInstalled(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["version"])) {
            $logging->log("Ensure install is checking versions") ;
            if ($this->askStatus() == true) {
                $logging->log("Already installed, checking version constraints") ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version operator is minimum") ;
                    $this->params["version-operator"] = "+" ; }
                $currentVersion = $this->getVersion() ;
                $currentVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($currentVersion->isCompatible() == true) {
                    $logging->log("Installed version {$currentVersion->shortVersionNumber} matches constraints, not installing") ; }
                else {
                    // @todo check if requested version is available
                    $logging->log("Installed version {$currentVersion->shortVersionNumber} does not match constraint, uninstalling") ;
                    // $this->unInstall() ;
                    /* @todo do a proper uninstall and install right version */ } }
            else {
                $logging->log("Not already installed, checking version constraints") ;
                if (!isset($this->params["version-operator"])) {
                    $logging->log("No --version-operator is set. Assuming requested version is minimum") ;
                    $this->params["version-operator"] = "+" ; }
                $recVersion = $this->getVersion("Recommended") ;
                $recVersion->setCondition($this->params["version"], $this->params["version-operator"]) ;
                if ($recVersion->isCompatible()) {
                    $logging->log("Requested version {$recVersion->shortVersionNumber} matches constraints, installing") ;
                    $this->install() ;  }
                else {
                    // @todo check if requested version is available
                    $logging->log("Installed version {$this->getVersion()} does not match constraint, uninstalling") ;
                    $this->unInstall() ;
                    /* @todo do a proper uninstall and install right version */ } } }
        else { // not checking version
            $logging->log("Ensure module install is not checking versions") ;
            if ($this->askStatus() == true) {
                $logging->log("Not installing as already installed") ; }
            else {
                $logging->log("Installing as not installed") ;
                $this->install(); } }
        return true;
    }

    public function install($autoPilot = null) {
        if (isset($this->params["hide-title"])) { $this->populateTinyTitle() ; }
        $this->showTitle();
        $this->programDataFolder = ($autoPilot)
          ? $autoPilot->{$this->autopilotDefiner."InstallDirectory"}
          : $this->askForProgramDataFolder();
        $this->programExecutorFolder = ($autoPilot)
          ? $autoPilot->{$this->autopilotDefiner."ExecutorDirectory"}
          : $this->askForProgramExecutorFolder();
        $this->doGitCommandWithErrorCheck();
        $this->deleteProgramDataFolderAsRootIfExists();
        $this->makeProgramDataFolderIfNeeded();
        $this->copyFilesToProgramDataFolder();
        $this->deleteExecutorIfExists();
        $this->populateExecutorFile();
        $this->saveExecutorFile();
        $this->deleteInstallationFiles();
        $this->changePermissions();
        $this->setInstallFlagStatus(true) ;
        if (isset($this->params["hide-completion"])) { $this->populateTinyCompletion(); }
        $this->showCompletion();
    }

    public function unInstall($autoPilot = null) {
        $this->showTitle();
        $this->programDataFolder = ($autoPilot)
          ? $autoPilot->{$this->autopilotDefiner}
          : $this->askForProgramDataFolder();
        $this->programExecutorFolder = $this->askForProgramExecutorFolder();
        $this->deleteProgramDataFolderAsRootIfExists();
        $this->deleteExecutorIfExists();
        $this->setInstallFlagStatus(false) ;
        $this->showCompletion();
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
        if (isset($this->params["program-data-directory"])) { return $this->params["program-data-directory"] ; }
        $question = 'What is the program data directory?';
        $question .= ' Found "/opt/'.$this->programNameMachine.'" - use this? (Enter nothing for yes, no end slash)';
        $input = (isset($this->params["yes"]) && $this->params["yes"]==true) ? "/opt/$this->programNameMachine" : self::askForInput($question);
        return ($input=="") ? "/opt/$this->programNameMachine" : $input ;
    }

  protected function askForProgramExecutorFolder(){
    $question = 'What is the program executor directory?';
    $question .= ' Found "/usr/bin" - use this? (Enter nothing for yes, No Trailing Slash)';
    $input = (isset($this->params["yes"]) && $this->params["yes"]==true) ? "/usr/bin" : self::askForInput($question);
    return ($input=="") ? "/usr/bin" : $input ;
  }

  protected function populateExecutorFile() {
      if (isset($this->params["no-executor"]))
    $arrayOfPaths = scandir($this->programDataFolder);
    $pathStr = "" ;
    foreach ($arrayOfPaths as $path) {
      $pathStr .= $this->programDataFolder.'/'.$path . PATH_SEPARATOR ; }
    $this->bootStrapData = "#!/usr/bin/php\n
<?php\n
set_include_path('" . $pathStr . "'.get_include_path() );
require('".$this->programDataFolder.DIRECTORY_SEPARATOR.$this->programExecutorTargetPath."');\n
?>";
  }

  protected function deleteProgramDataFolderAsRootIfExists(){
    if ( is_dir($this->programDataFolder)) {
      $command = 'rm -rf '.$this->programDataFolder;
      self::executeAndOutput($command, "Program Data Folder $this->programDataFolder Deleted if existed"); }
    return true;
  }

  protected function makeProgramDataFolderIfNeeded(){
    if (!file_exists($this->programDataFolder)) {
      mkdir($this->programDataFolder,  0777, true); }
  }

  protected function copyFilesToProgramDataFolder(){
    $command = 'cp -r '.$this->tempDir.DIRECTORY_SEPARATOR.$this->programNameMachine.
        DIRECTORY_SEPARATOR.'* '.$this->programDataFolder;
    return self::executeAndOutput($command, "Program Data folder populated");
  }

  protected function deleteExecutorIfExists(){
    $command = 'rm -f '.$this->programExecutorFolder.DIRECTORY_SEPARATOR.$this->programNameMachine;
    self::executeAndOutput($command, "Program Executor Deleted if existed");
    return true;
  }

  protected function deleteInstallationFiles(){
    $command = 'rm -rf '.$this->tempDir.'/'.$this->programNameMachine;
    self::executeAndOutput($command);
  }

  protected function saveExecutorFile(){
    $this->populateExecutorFile();
    return file_put_contents($this->programExecutorFolder.'/'.$this->programNameMachine, $this->bootStrapData);
  }

  protected function changePermissions(){
    $command = "chmod -R 775 $this->programDataFolder";
    self::executeAndOutput($command);
    $command = "chmod 775 $this->programExecutorFolder/$this->programNameMachine";
    self::executeAndOutput($command);
  }

  protected function doGitCommandWithErrorCheck(){
    $data = $this->doGitCommand();
    print $data;
    if ( substr($data, 0, 5) == "error" ) { return false; }
    return true;
  }

  protected function doGitCommand(){
    $data = "";
    foreach ($this->fileSources as $fileSource) {
      $command  = 'git clone ';
      if (isset($fileSource[3]) &&
        $fileSource[3] = true) { $command .= '--recursive ';}
      if ($fileSource[2] != null) { $command .= '-b '.$fileSource[2].' ';}
      $command .= escapeshellarg($fileSource[0]).' ';
      $command .= ' '.$this->tempDir.DIRECTORY_SEPARATOR.$this->programNameMachine;
      if ($fileSource[1] != null) { $command .= DIRECTORY_SEPARATOR.$fileSource[1];}
      echo $command;
      $data .= self::executeAndLoad($command); }
    return $data;
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

}