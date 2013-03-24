<?php

Namespace Model;

class BasePHPApp extends Base {


  private $fileSource = "http://github.com/phpengine/boxboss-source-phpunit";
  private $tempDir = '/tmp';
  private $programNameMachine = "phpunit"; // command to be used on command line
  private $autopilotDefiner = "PHPUnit35";
  private $programNameFriendly = " PHP Unit ! "; // 12 chars

  protected $startDirectory;
  protected $titleData;

  protected $completionData;
  protected $bootStrapData;

  protected $programDataFolder;
  protected $programExecutorFolder;

  public function __construct() {
    $this->populateStartDirectory();
    $this->populateTitle();
    $this->populateCompletion();
    $this->populateExecutorFile();
  }

  private function populateExecutorFile() {
    $this->bootStrapData = "#!/usr/bin/php\n
<?php\n
require('".$this->programDataFolder."/".$this->programNameMachine."');\n
?>";
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

  public function askWhetherUnInstallPHPApp() {
    return $this->performPHPAppUnInstall();
  }

  private function performPHPAppInstall() {
    $doInstall = $this->askWhetherToInstallPHPApp();
    if (!$doInstall) { return false; }
    $this->install();
    return true;
  }

  private function performPHPAppUnInstall() {
    $doUnInstall = $this->askWhetherToUnInstallPHPApp();
    if (!$doUnInstall) { return false; }
    $this->unInstall($this->installDir, $this->executor);
    return true;
  }
  public function runAutoPilotPHPAppInstall($autoPilot){
    $doInstall = $autoPilot->PHPUnitInstallExecute;
    if ($doInstall !== true) { return false; }
    $this->install($autoPilot);
    return true;
  }

  public function runAutoPilotPHPAppUnInstall($autoPilot){
    $doUnInstall = $autoPilot->hostEditorDeletionExecute;
    if ($doUnInstall !== true) { return false; }
    $this->unInstall($autoPilot);
    return true;
  }

  public function install($autoPilot = null) {
    $this->showTitle();
    $this->programDataFolder =$this->askForProgramDataFolder();
    $this->programExecutorFolder = $this->askForProgramExecutorFolder();
    $params[0] = $this->fileSource;
    $params[1] = $this->programNameMachine;
    $this->doGitCommandWithErrorCheck($params);
    $this->deleteProgramDataFolderAsRootIfExists();
    $this->makeProgramDataFolderIfNeeded();
    $this->copyFilesToProgramDataFolder();
    $this->deleteExecutorIfExists();
    $this->saveExecutorFile();
    $this->deleteInstallationFiles();
    $this->changePermissions();
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
    $command = 'cp -r '.dirname(__FILE__).'/* '.$this->programDataFolder;
    return self::executeAndOutput($command, "Program Data folder populated");
  }

  private function deleteExecutorIfExists(){
    $command = 'rm -f '.$this->programExecutorFolder.'/'.$this->programNameMachine;
    self::executeAndOutput($command, "Program Executor Deleted  if existed");
    return true;
  }

  private function deleteInstallationFiles(){
    $command = 'rm -rf '.$this->startDirectory.'/'.$this->programNameMachine;
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

  private function doGitCommandWithErrorCheck($params){
    $data = $this->doGitCommand($params);
    print $data;
    if ( substr($data, 0, 5) == "error" ) { return false; }
    return true;
  }

  private function doGitCommand($params){
    $projectOriginRepo = $params[0];
    $customCloneFolder = (isset($params[1]) && ($params[1]) != "none") ? $params[1] : null ;
    $command  = 'git clone '.escapeshellarg($projectOriginRepo);
    if (isset($customCloneFolder)) { $command .= ' '.escapeshellarg($customCloneFolder); }
    $nameInRepo = substr($projectOriginRepo, strrpos($projectOriginRepo, '/', -1) );
    $this->projectDirectory = (isset($customCloneFolder)) ? $customCloneFolder : $nameInRepo ;
    return self::executeAndLoad($command);
  }

}