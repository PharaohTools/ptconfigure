<?php

Namespace Core;

class View {

  public function executeView($view, Array $viewVars) {
      $vvLayoutCond1 = (isset($viewVars["params"]["output-format"])
          && $viewVars["params"]["output-format"] == "HTML") ;
      $vvLayoutCond2 = (isset($viewVars["params"]["output-format"])
          && $viewVars["params"]["output-format"] != "cli"
          && $viewVars["params"]["output-format"] != "HTML") ;
    if (!isset($viewVars["layout"])) {
        if ($vvLayoutCond1) {
            $viewVars["layout"] = "DefaultHTML" ; }
        else if ($vvLayoutCond2) {
            $viewVars["layout"] = "blank" ; }
        else {
            $viewVars["layout"] = "default" ; } }
    $templateData = $this->loadTemplate ($view, $viewVars) ;
    $data = $this->loadLayout ( $viewVars["layout"], $templateData, $viewVars) ;
    $this->renderAll($data) ;
  }

  private function loadLayout ($layout, $templateData, Array $pageVars) {
    ob_start();
    $viewFileName = ucfirst($layout)."Layout.tpl.php";
    if ($this->loadViewFile($viewFileName, $pageVars, $templateData) == true) {
      return ob_get_clean(); }
    else {
      die ("View Layout Not Found\n"); }
  }

  private function loadTemplate ($view, Array $pageVars) {
    ob_start();
    $outputFormat = "" ;
    if (isset($pageVars["params"]["output-format"])) {
      $outputFormat = strtoupper($pageVars["params"]["output-format"]); }
    $viewFileName = ucfirst($view).$outputFormat."View.tpl.php";
    if ($this->loadViewFile($viewFileName, $pageVars) == true) {
      return ob_get_clean(); }
    else {
      die ("View Template $viewFileName for $outputFormat Not Found\n"); }
  }

  private function renderAll($processedData) {
    echo $processedData;
  }

  private function renderMessages($pageVars) {
    $outVar = '';
    if (isset($pageVars["messages"])) {
      foreach ($pageVars["messages"] as $message ) {
        $outVar .= '***ERROR: '. $message."\n"; } }
    $outVar .= "\n";
    return $outVar;
  }

  private function loadViewFile($viewFileName, $pageVars, $templateData=null) {
    $allModuleParentDirectories = array("Extensions", "Modules", "Core");
    foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
      $modulesParentDirFullPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
      $modulesIndividualDirectories = scandir($modulesParentDirFullPath);
      foreach ($modulesIndividualDirectories as $singleModuleDir) {
        if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
          if ( is_dir($modulesParentDirFullPath.DIRECTORY_SEPARATOR.$singleModuleDir)) { // if is a dir
            $fileNameAndPath =
              $modulesParentDirFullPath . DIRECTORY_SEPARATOR . $singleModuleDir .
              DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $viewFileName;
            if (is_readable($fileNameAndPath)) {
              require_once $fileNameAndPath;
              return true;
            }
          }
        }
      }
    }
    return false;
  }

}