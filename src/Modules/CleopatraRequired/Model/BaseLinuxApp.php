<?php

Namespace Model;

class BaseLinuxApp extends Base {

  protected $installCommands;
  protected $uninstallCommands;
  protected $installUserName;
  protected $installUserHomeDir;
  protected $registeredPreInstallFunctions;
  protected $registeredPreUnInstallFunctions;
  protected $registeredPostInstallFunctions;
  protected $registeredPostUnInstallFunctions;
  protected $programExecutorCommand;

  public function __construct($params) {
    parent::__construct($params);
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

  public function askInstall() {
    return $this->askWhetherToInstallLinuxApp();
  }

  /*
   * @todo
  public function askUnInstall() {
    return $this->askWhetherToUnInstallPHPApp();
  }
  */

  public function runAutoPilotInstall($autoPilot) {
    return $this->runAutoPilotLinuxAppInstall($autoPilot);
  }

  public function runAutoPilotUnInstall($autoPilot) {
    return $this->runAutoPilotLinuxAppUnInstall($autoPilot);
  }

  public function askWhetherUnInstallLinuxApp() {
    return $this->performLinuxAppUnInstall();
  }

  private function performLinuxAppInstall() {
    $doInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
      true : $this->askWhetherToInstallLinuxAppToScreen();
    if (!$doInstall) { return false; }
    return $this->install();
  }

  private function performLinuxAppUnInstall() {
    $doUnInstall = (isset($this->params["yes"]) && $this->params["yes"]==true) ?
      true : $this->askWhetherToUnInstallLinuxAppToScreen();
    if (!$doUnInstall) { return false; }
    $this->unInstall();
    return true;
  }
  public function runAutoPilotLinuxAppInstall($autoPilot){
    $this->setAutoPilotVariables($autoPilot);
    $this->install($autoPilot);
    return true;
  }

  public function runAutoPilotLinuxAppUnInstall($autoPilot){
    $this->unInstall($autoPilot);
    return true;
  }

  public function install($autoPilot = null) {
    ob_start();
    $this->showTitle();
    $this->executePreInstallFunctions($autoPilot);
    $this->doInstallCommand();
    $this->executePostInstallFunctions($autoPilot);
    if ($this->programDataFolder) {
      $this->changePermissions($this->programDataFolder); }
    $this->extraCommands();
    $this->showCompletion();
    return ob_get_clean();
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

  protected function askForInstallUserName($autoPilot=null){
    if (isset($autoPilot) &&
      $autoPilot->{$this->autopilotDefiner."InstallUserName"} ) {
      $this->installUserName
        = $autoPilot->{$this->autopilotDefiner."InstallUserName"}; }
    else {
      $question = "Enter User To Install As:";
      $this->installUserName = self::askForInput($question, true); }
  }

  protected function askForInstallUserHomeDir($autoPilot=null){
    if (isset($autoPilot) &&
      $autoPilot->{$this->autopilotDefiner."InstallUserHomeDir"} ) {
      $this->installUserHomeDir
        = $autoPilot->{$this->autopilotDefiner."InstallUserHomeDir"}; }
    else {
      $question = "Enter Install User Home Dir:";
      $this->installUserHomeDir = self::askForInput($question, true); }
  }

  protected function askForInstallDirectory($autoPilot=null){
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
        $this->$func($autoPilot); } }
  }

  private function executePostInstallFunctions($autoPilot){
    if (isset($this->registeredPostInstallFunctions) &&
      is_array($this->registeredPostInstallFunctions) &&
      count($this->registeredPostInstallFunctions) >0) {
      foreach ($this->registeredPostInstallFunctions as $func) {
        $this->$func($autoPilot); } }
  }

  private function executePreUnInstallFunctions($autoPilot){
    if (isset($this->registeredPreUnInstallFunctions) &&
      is_array($this->registeredPreUnInstallFunctions) &&
      count($this->registeredPreUnInstallFunctions) >0) {
      foreach ($this->registeredPreUnInstallFunctions as $func) {
        $this->$func($autoPilot); } }
  }

  private function executePostUnInstallFunctions($autoPilot){
    if (isset($this->registeredPostUnInstallFunctions) &&
      is_array($this->registeredPostUnInstallFunctions) &&
      count($this->registeredPostUnInstallFunctions) >0) {
      foreach ($this->registeredPostUnInstallFunctions as $func) {
        $this->$func($autoPilot); } }
  }

  private function doInstallCommand(){
    self::swapCommandArrayPlaceHolders($this->installCommands);
    self::executeAsShell($this->installCommands);
  }

  private function changePermissions($autoPilot, $target=null){
    if ($target != null) {
      $command = "chmod -R 775 $target";
      self::executeAndOutput($command); }
  }

  private function deleteExecutorIfExists(){
    $command = 'rm -f '.$this->programExecutorFolder.DIRECTORY_SEPARATOR.$this->programNameMachine;
    self::executeAndOutput($command, "Program Executor Deleted if existed");
    return true;
  }

  private function saveExecutorFile(){
    $this->populateExecutorFile();
    $executorPath = $this->programExecutorFolder.DIRECTORY_SEPARATOR.$this->programNameMachine;
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