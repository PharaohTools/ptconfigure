<?php

Namespace Model;

class BaseLinuxApp extends Base {


  protected $installCommands;
  protected $uninstallCommands;
  protected $programNameMachine ;
  protected $autopilotDefiner ;
  protected $programNameFriendly;
  protected $programDataFolder;
  protected $programNameInstaller;

  protected $startDirectory;
  protected $titleData;

  protected $completionData;
  protected $bootStrapData;
  protected $extraBootStrap;

  protected $extraCommandsArray;

  public function __construct() {
    $this->populateCompletion();
  }

  public function initialize() {
    $this->populateTitle();
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

  public function askWhetherToInstallLinuxApp() {
    return $this->performLinuxAppInstall();
  }

  public function askWhetherUnInstallLinuxApp() {
    return $this->performLinuxAppUnInstall();
  }

  private function performLinuxAppInstall() {
    $doInstall = $this->askWhetherToInstallLinuxAppToScreen();
    if (!$doInstall) { return false; }
    $this->install();
    return true;
  }

  private function performLinuxAppUnInstall() {
    $doUnInstall = $this->askWhetherToUnInstallLinuxAppToScreen();
    if (!$doUnInstall) { return false; }
    $this->unInstall();
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
    $this->doInstallCommand();
    $this->changePermissions();
    $this->extraCommands();
    $this->showCompletion();
  }

  public function unInstall($autoPilot = null) {
    $this->showTitle();
    $this->doUnInstallCommand();
    $this->extraCommands();
    $this->showCompletion();
  }

  private function showTitle() {
    print $this->titleData ;
  }

  private function showCompletion() {
    print $this->completionData ;
  }

  private function askWhetherToInstallLinuxAppToScreen(){
    $question = "Install ".$this->programNameInstaller." ?";
    return self::askYesOrNo($question);
  }

  private function askWhetherToUnInstallLinuxAppToScreen(){
    $question = "Un Install ".$this->programNameInstaller." ?";
    return self::askYesOrNo($question);
  }

  private function changePermissions(){
    $command = "chmod -R 775 $this->programDataFolder";
    self::executeAndOutput($command);
  }

  private function doInstallCommand(){
    $data = "";
    foreach ($this->installCommands as $command) {
      str_replace("****PROGDIR****", $this->programDataFolder, $command);
      $data .= self::executeAndOutput($command); }
    return $data;
  }

  private function doUnInstallCommand(){
    $data = "";
    foreach ($this->uninstallCommands as $command) {
      str_replace("****PROGDIR****", $this->programDataFolder, $command);
      $data .= self::executeAndOutput($command); }
    return $data;
  }

  private function extraCommands(){
    $data = "";
    foreach ($this->extraCommandsArray as $command) {
      str_replace("****PROGDIR****", $this->programDataFolder, $command);
      $data .= self::executeAndOutput($command); }
    return $data;
  }

}