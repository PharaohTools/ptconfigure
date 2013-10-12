<?php

Namespace Controller ;

class Base {

  public $content;
  protected $registeredModels = array();

  public function __construct() {
    $this->content = array(); }

  public function checkDefaultActions($pageVars, $ignored_actions=array(), $thisModel=null) {
    $this->content["route"] = $pageVars["route"];
    $this->content["messages"] = $pageVars["messages"];
    $action = $pageVars["route"]["action"];
    if ($action=="help" && !in_array($action, $ignored_actions)) {
      $helpModel = new \Model\Help();
      $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
      return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

    if (isset($thisModel)) {
        if ($action=="install" && !in_array($action, $ignored_actions)) {
            $this->content["params"] = $thisModel->params;
            $this->content["appName"] = $thisModel->autopilotDefiner;
            $this->content["appInstallResult"] = $thisModel->askInstall();
            return array ("type"=>"view", "view"=>"appInstall", "pageVars"=>$this->content); }

        if ($action=="uninstall" && !in_array($action, $ignored_actions)) {
            $this->content["params"] = $thisModel->params;
            $this->content["appName"] = $thisModel->autopilotDefiner;
            $this->content["appInstallResult"] = $thisModel->askUninstall();
            return array ("type"=>"view", "view"=>"appUninstall", "pageVars"=>$this->content); }

        if ($action=="status" && !in_array($action, $ignored_actions)) {
            $this->content["params"] = $thisModel->params;
            $this->content["appName"] = $thisModel->autopilotDefiner;
            $this->content["appStatusResult"] = $thisModel->askStatus();
            return array ("type"=>"view", "view"=>"appStatus", "pageVars"=>$this->content); } }

     else if (!isset($thisModel)) {
         $this->content["messages"][] = "Required Model Missing. Cannot Continue.";
         return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }

    return false;
  }

  protected function checkForRegisteredModels($params, $modelOverrides) {
    $modelsToCheck = (isset($modelOverrides)) ? $modelOverrides : $this->registeredModels ;
    foreach ($modelsToCheck as $modelClassNameOrArray) {
      if ( is_array($modelClassNameOrArray) ) {
        $currentKeys = array_keys($modelClassNameOrArray) ;
        $currentKey = $currentKeys[0] ;
        $fullClassName = '\Model\\'.$currentKey;
        $moduleModelFactory = new $fullClassName($params);
        $compatibleObject = $moduleModelFactory::getModel($params) ;
        if ( !is_object($compatibleObject) ) {
          echo "Module $currentKey Does not have compatible models for this system: \n"; } }
      else {
        $fullClassName = '\Model\\'.$modelClassNameOrArray;
        $moduleModelFactory = new $fullClassName($params);
        $compatibleObject = $moduleModelFactory::getModel($params) ;
        if ( !is_object($compatibleObject) ) {
            echo "Module $modelClassNameOrArray Does not have compatible models for this system: \n"; } } }
    echo "All expected and compatible Models found"."\n\n";
  }

  protected function executeMyRegisteredModels($params = null) {
    foreach ($this->registeredModels as $modelClassNameOrArray) {
      if ( is_array($modelClassNameOrArray) ) {
        $currentKeys = array_keys($modelClassNameOrArray) ;
        $currentKey = $currentKeys[0] ;
        $fullClassName = '\Model\\'.$currentKey;}
      else {
        $fullClassName = '\Model\\'.$modelClassNameOrArray; }
      $currentModel = new $fullClassName($params);
      $miniRay = array();
      $miniRay["appName"] = $currentModel->programNameInstaller;
      $miniRay["installResult"] = $currentModel->askInstall();
      $this->content["results"][] = $miniRay ; }
  }

  protected function executeMyRegisteredModelsAutopilot($autoPilot, $params = null) {
    foreach ($autoPilot->steps as $modelArray) {
        $currentKeys = array_keys($modelArray) ;
        $currentKey = $currentKeys[0] ;
        $fullClassName = '\Model\\'.$currentKey;
        $currentModel = new $fullClassName($params);
        $miniRay = array();
        $miniRay["appName"] = $currentModel->programNameInstaller;
        $miniRay["installResult"] = $currentModel->runAutoPilotInstall($modelArray);
        $this->content["results"][] = $miniRay ; }
  }


  protected function getModelAndCheckDependencies($module, $pageVars) {
    $myInfo = \Core\AutoLoader::getSingleInfoObject($module);
    $myModuleAndDependencies = array_merge(array($module), $myInfo->dependencies() ) ;
    $this->checkForRegisteredModels($pageVars["route"]["extraParams"], $myModuleAndDependencies) ;
    $thisModel = \Model\SystemDetectionFactory::getCompatibleModel($module, "Installer", $pageVars["route"]["extraParams"]);
    return $thisModel;
  }

}