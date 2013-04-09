<?php

Namespace Model;

class BaseLinuxApp extends Base {

  protected $installCommands;
  protected $uninstallCommands;
  protected $installUserName;
  protected $installUserHomeDir;
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
  protected $registeredPreInstallFunctions;
  protected $registeredPreUnInstallFunctions;
  protected $registeredPostInstallFunctions;
  protected $registeredPostUnInstallFunctions;
  protected $programExecutorCommand;
  protected $programExecutorFolder;
  protected $programExecutorTargetPath;

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
  public function runAutoPilotLinuxAppInstall($autoPilot){
    $doInstall = $autoPilot->{$this->autopilotDefiner."InstallExecute"};
    if ($doInstall !== true) { return false; }
    $this->install($autoPilot);
    return true;
  }

  public function runAutoPilotLinuxAppUnInstall($autoPilot){
    $doUnInstall = $autoPilot->{$this->autopilotDefiner."InstallExecute"};
    if ($doUnInstall !== true) { return false; }
    $this->unInstall($autoPilot);
    return true;
  }

  public function install($autoPilot = null) {
    $this->showTitle();
    $this->executePreInstallFunctions($autoPilot);
    $this->doInstallCommand();
    $this->executePostInstallFunctions($autoPilot);
    if ($this->programDataFolder) {
      $this->changePermissions($this->programDataFolder); }
    $this->extraCommands();
    $this->showCompletion();
  }

  public function unInstall($autoPilot = null) {
    $this->showTitle();
    $this->executePreUnInstallFunctions($autoPilot);
    $this->doUnInstallCommand();
    $this->executePostUninstallFunctions($autoPilot);
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
    $question = "Install ".$this->programNameInstaller."?";
    return self::askYesOrNo($question);
  }

  private function askWhetherToUnInstallLinuxAppToScreen(){
    $question = "Un Install ".$this->programNameInstaller."?";
    return self::askYesOrNo($question);
  }

  private function askForInstallUserName($autoPilot=null){
    if (isset($autoPilot) &&
      $autoPilot->{$this->autopilotDefiner."InstallUserName"} ) {
      $this->installUserName
        = $autoPilot->{$this->autopilotDefiner."InstallUserName"}; }
    else {
      $question = "Enter User To Install As:";
      $this->installUserName = self::askForInput($question, true); }
  }

  private function askForInstallUserHomeDir($autoPilot=null){
    if (isset($autoPilot) &&
      $autoPilot->{$this->autopilotDefiner."InstallUserHomeDir"} ) {
      $this->installUserHomeDir
        = $autoPilot->{$this->autopilotDefiner."InstallUserHomeDir"}; }
    else {
      $question = "Enter Install User Home Dir:";
      $this->installUserHomeDir = self::askForInput($question, true); }
  }

  private function askForInstallDirectory($autoPilot=null){
    if (isset($autoPilot) &&
      $autoPilot->{$this->autopilotDefiner."InstallDirectory"} ) {
      $this->programDataFolder
        = $autoPilot->{$this->autopilotDefiner."InstallDirectory"}; }
    else {
      $question = "Enter Install Directory:";
      $this->programDataFolder = self::askForInput($question, true); }
  }

  private function executePreInstallFunctions($autoPilot){
    if (isset($this->registeredPreInstallFunctions) &&
      is_array($this->registeredPreInstallFunctions) &&
      count($this->registeredPreInstallFunctions) >0) {
      foreach ($this->registeredPreInstallFunctions as $func) {
        self::$func($autoPilot); } }
  }

  private function executePostInstallFunctions($autoPilot){
    if (isset($this->registeredPostInstallFunctions) &&
      is_array($this->registeredPostInstallFunctions) &&
      count($this->registeredPostInstallFunctions) >0) {
      foreach ($this->registeredPostInstallFunctions as $func) {
        self::$func($autoPilot); } }
  }

  private function executePreUnInstallFunctions($autoPilot){
    if (isset($this->registeredPreUnInstallFunctions) &&
      is_array($this->registeredPreUnInstallFunctions) &&
      count($this->registeredPreUnInstallFunctions) >0) {
      foreach ($this->registeredPreUnInstallFunctions as $func) {
        self::$func($autoPilot); } }
  }

  private function executePostUnInstallFunctions($autoPilot){
    if (isset($this->registeredPostUnInstallFunctions) &&
      is_array($this->registeredPostUnInstallFunctions) &&
      count($this->registeredPostUnInstallFunctions) >0) {
      foreach ($this->registeredPostUnInstallFunctions as $func) {
        self::$func($autoPilot); } }
  }

  private function doInstallCommand(){
    self::swapCommandArrayPlaceHolders($this->installCommands);
    self::executeAsShell($this->installCommands);
  }

  private function changePermissions($autoPilot, $target=null){
    $command = "chmod -R 775 $target";
    self::executeAndOutput($command);
  }

  private function deleteExecutorIfExists(){
    $command = 'rm -f '.$this->programExecutorFolder.'/'.$this->programNameMachine;
    self::executeAndOutput($command, "Program Executor Deleted if existed");
    return true;
  }

  private function saveExecutorFile(){
    $this->populateExecutorFile();
    $executorPath = $this->programExecutorFolder.'/'.$this->programNameMachine;
    file_put_contents($executorPath, $this->bootStrapData);
    $this->changePermissions(null, $executorPath);
  }

  private function populateExecutorFile() {
    $this->bootStrapData = "#!/usr/bin/php\n
<?php\n
exec('".$this->programExecutorCommand."');\n
?>";
  }

  private function doUnInstallCommand(){
    self::swapCommandArrayPlaceHolders($this->uninstallCommands);
    self::executeAsShell($this->uninstallCommands);
  }

  private function extraCommands(){
    self::swapCommandArrayPlaceHolders($this->extraCommandsArray);
    self::executeAsShell($this->extraCommandsArray);
  }

  private function swapCommandArrayPlaceHolders(&$commandArray) {
    $this->swapCommandDirs($commandArray);
    $this->swapInstallUserDetails($commandArray);
  }

  private function swapCommandDirs(&$commandArray) {
    if (is_array($commandArray) && count($commandArray)>0) {
      foreach ($commandArray as &$comm) {
        $comm = str_replace("****PROGDIR****", $this->programDataFolder, $comm);
        $comm = str_replace("****PROG EXECUTOR****", $this->programDataFolder,
          $comm); } }
  }

  private function swapInstallUserDetails(&$commandArray) {
    if (is_array($commandArray) && count($commandArray)>0) {
      foreach ($commandArray as &$comm) {
        $comm = str_replace("****INSTALL USER NAME****", $this->installUserName,
          $comm);
        $comm = str_replace("****INSTALL USER HOME DIR****",
          $this->installUserHomeDir, $comm); } }
  }

}