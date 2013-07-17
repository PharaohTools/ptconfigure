<?php

Namespace Model;

class BasePHPApp extends Base {

  protected $fileSources;
  protected $tempDir = '/tmp';

  public function __construct() {
    $this->populateStartDirectory();
    $this->populateCompletion();
  }

  public function initialize() {
    $this->populateTitle();
  }

  private function populateStartDirectory() {
    $this->startDirectory = str_replace("/$this->programNameMachine", "",
      $this->tempDir);
  }

  private function populateTitle() {
    $this->titleData = <<<TITLE
*******************************
*   Golden Contact Computing  *
*         $this->programNameFriendly        *
*******************************

TITLE;
  }

  private function populateCompletion() {
    $this->completionData = <<<COMPLETION
... All done!
*******************************
Thanks for installing , visit www.gcsoftshop.co.uk for more

COMPLETION;
  }

  public function askWhetherToInstallPHPApp() {
    return $this->performPHPAppInstall();
  }

  public function askInstall() {
    return $this->askWhetherToInstallPHPApp();
  }

  /*
   * @todo
  public function askUnInstall() {
    return $this->askWhetherToUnInstallPHPApp();
  }
  */

  public function runAutoPilotInstall($autoPilot) {
    return $this->runAutoPilotPHPAppInstall($autoPilot);
  }

  public function runAutoPilotUnInstall($autoPilot) {
    return $this->runAutoPilotPHPAppUnInstall($autoPilot);
  }

  public function askWhetherUnInstallPHPApp() {
    return $this->performPHPAppUnInstall();
  }

  private function performPHPAppInstall() {
    $doInstall = $this->askWhetherToInstallPHPAppToScreen();
    if (!$doInstall) { return false; }
    $this->install();
    return true;
  }

  private function performPHPAppUnInstall() {
    $doUnInstall = $this->askWhetherToUnInstallPHPAppToScreen();
    if (!$doUnInstall) { return false; }
    $this->unInstall();
    return true;
  }
  public function runAutoPilotPHPAppInstall($autoPilot){
    $doInstall = $autoPilot->{$this->autopilotDefiner."InstallExecute"};
    if ($doInstall !== true) { return false; }
    $this->install($autoPilot);
    return true;
  }

  public function runAutoPilotPHPAppUnInstall($autoPilot){
    $doUnInstall = $autoPilot->{$this->autopilotDefiner."UnInstallExecute"};
    if ($doUnInstall !== true) { return false; }
    $this->unInstall($autoPilot);
    return true;
  }

  public function install($autoPilot = null) {
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
    $this->extraCommands();
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
    $this->showCompletion();
  }

  private function showTitle() {
    print $this->titleData ;
  }

  private function showCompletion() {
    print $this->completionData ;
  }

  private function askWhetherToInstallPHPAppToScreen(){
    $question = "Install ".$this->programNameInstaller." ?";
    return self::askYesOrNo($question);
  }

  private function askWhetherToUnInstallPHPAppToScreen(){
    $question = "Un Install ".$this->programNameInstaller." ?";
    return self::askYesOrNo($question);
  }

  private function askForProgramDataFolder() {
    $question = 'What is the program data directory?';
    $question .= ' Found "/opt/'.$this->programNameMachine.'" - use this? (Enter nothing for yes, no end slash)';
    $input = self::askForInput($question);
    return ($input=="") ? "/opt/$this->programNameMachine" : $input ;
  }

  private function askForProgramExecutorFolder(){
    $question = 'What is the program executor directory?';
    $question .= ' Found "/usr/bin" - use this? (Enter nothing for yes, No Trailing Slash)';
    $input = self::askForInput($question);
    return ($input=="") ? "/usr/bin" : $input ;
  }

  private function populateExecutorFile() {
    $arrayOfPaths = scandir($this->programDataFolder);
    $pathStr = "" ;
    foreach ($arrayOfPaths as $path) {
      $pathStr .= $this->programDataFolder.'/'.$path . PATH_SEPARATOR ; }
    $this->bootStrapData = "#!/usr/bin/php\n
<?php\n
set_include_path('" . $pathStr . "'.get_include_path() );
require('".$this->programDataFolder."/".$this->programExecutorTargetPath."');\n
?>";
  }

  private function deleteProgramDataFolderAsRootIfExists(){
    if ( is_dir($this->programDataFolder)) {
      $command = 'rm -rf '.$this->programDataFolder;
      self::executeAndOutput($command, "Program Data Folder $this->programDataFolder Deleted if existed"); }
    return true;
  }

  private function makeProgramDataFolderIfNeeded(){
    if (!file_exists($this->programDataFolder)) {
      mkdir($this->programDataFolder,  0777, true); }
  }

  private function copyFilesToProgramDataFolder(){
    $command = 'cp -r '.$this->tempDir.'/'.$this->programNameMachine.'/* '.$this->programDataFolder;
    return self::executeAndOutput($command, "Program Data folder populated");
  }

  private function deleteExecutorIfExists(){
    $command = 'rm -f '.$this->programExecutorFolder.'/'.$this->programNameMachine;
    self::executeAndOutput($command, "Program Executor Deleted if existed");
    return true;
  }

  private function deleteInstallationFiles(){
    $command = 'rm -rf '.$this->tempDir.'/'.$this->programNameMachine;
    self::executeAndOutput($command);
  }

  private function saveExecutorFile(){
    $this->populateExecutorFile();
    return file_put_contents($this->programExecutorFolder.'/'.$this->programNameMachine, $this->bootStrapData);
  }

  private function changePermissions(){
    $command = "chmod -R 775 $this->programDataFolder";
    self::executeAndOutput($command);
    $command = "chmod 775 $this->programExecutorFolder/$this->programNameMachine";
    self::executeAndOutput($command);
  }

  private function doGitCommandWithErrorCheck(){
    $data = $this->doGitCommand();
    print $data;
    if ( substr($data, 0, 5) == "error" ) { return false; }
    return true;
  }

  private function doGitCommand(){
    $data = "";
    foreach ($this->fileSources as $fileSource) {
      $command  = 'git clone ';
      if (isset($fileSource[3]) &&
        $fileSource[3] = true) { $command .= '--recursive ';}
      if ($fileSource[2] != null) { $command .= '-b '.$fileSource[2].' ';}
      $command .= escapeshellarg($fileSource[0]).' ';
      $command .= ' '.$this->programNameMachine;
      if ($fileSource[1] != null) { $command .= '/'.$fileSource[1];}
      $data .= self::executeAndLoad($command); }
    return $data;
  }

  private function extraCommands(){
    $data = "";
    if (is_array($this->extraCommandsArray)
      && count($this->extraCommandsArray)>0 ) {
      foreach ($this->extraCommandsArray as $command) {
        str_replace("****PROGDIR****", $this->programDataFolder, $command);
        $data .= self::executeAndLoad($command); } }
    return $data;
  }

}