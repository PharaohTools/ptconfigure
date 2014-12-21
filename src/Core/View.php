<?php

Namespace Core;

class View {

  public function executeView($view, Array $viewVars) {
      $ep = (isset($viewVars["route"]["extraParams"])) ? $viewVars["route"]["extraParams"] : array() ;
      $baseMod = new \Model\Base($ep) ;
      $viewVars["params"] = $baseMod->params ;
      $vvLayoutCond1 = (isset($viewVars["params"]["output-format"])
          && $viewVars["params"]["output-format"] == "HTML") ;
      $vvLayoutCond2 = (isset($viewVars["params"]["output-format"])
          && $viewVars["params"]["output-format"] != "cli"
          && $viewVars["params"]["output-format"] != "HTML") ;
      if (!isset($viewVars["layout"])) {
          if ($vvLayoutCond1) { $viewVars["layout"] = "DefaultHTML" ; }
          else if ($vvLayoutCond2) { $viewVars["layout"] = "blank" ; }
          else { $viewVars["layout"] = "default" ; } }
      $templateData = $this->loadTemplate ($view, $viewVars) ;
      $data = $this->loadLayout ( $viewVars["layout"], $templateData, $viewVars) ;
      $this->renderAll($data) ;
  }

  public function loadLayout ($layout, $templateData, Array $pageVars) {
      ob_start();
      $viewFileName = ucfirst($layout)."Layout.tpl.php";
      if ($this->loadViewFile($viewFileName, $pageVars, $templateData) == true) {
          return ob_get_clean(); }
      else {
          // @todo no! dont die
          die ("View Layout Not Found\n"); }
  }

  public function loadTemplate ($view, Array $pageVars) {
    ob_start();
    $outputFormat = "" ;
    if (isset($pageVars["params"]["output-format"])) {
      $outputFormat = strtoupper($pageVars["params"]["output-format"]); }
    if (isset($pageVars["params"]["output-format"])  && $pageVars["params"]["output-format"]=="AUTO") {
      $outputFormat = strtoupper($pageVars["params"]["output-format"]); }
    $viewFileName = ucfirst($view).$outputFormat."View.tpl.php";
    if ($this->loadViewFile($viewFileName, $pageVars) == true) {
        return ob_get_clean(); }
    else if (substr($viewFileName, strlen($viewFileName)-16, 16) =="AUTOView.tpl.php" && $this->loadViewFile("DefaultAUTOView.tpl.php", $pageVars) == true) {
        return ob_get_clean(); }
    else {
        // @todo no! dont die
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

  public function loadViewFile($viewFileName, $pageVars, $templateData=null) {
    $allModuleParentDirectories = array("Extensions", "Modules", "Core");
    foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
        if ($oneModuleParentDirectory != "Core") {
            $modulesParentDirFullPath = dirname(__FILE__).DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
            $modulesIndividualDirectories = scandir($modulesParentDirFullPath);
            foreach ($modulesIndividualDirectories as $singleModuleDir) {
              if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                  if ( is_dir($modulesParentDirFullPath.DIRECTORY_SEPARATOR.$singleModuleDir)) { // if is a dir
                      $fileNameAndPath = $modulesParentDirFullPath . DIRECTORY_SEPARATOR . $singleModuleDir . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $viewFileName;
                      if (is_readable($fileNameAndPath)) {
                          // @todo require in exception
                          require $fileNameAndPath;
                          return true; } } } } }
        else {
            $modulesParentDirFullPath = dirname(__FILE__).DIRECTORY_SEPARATOR."Base".DIRECTORY_SEPARATOR."Views" ;
            $modulesParentHelpDirFullPath = dirname(__FILE__).DIRECTORY_SEPARATOR."Help".DIRECTORY_SEPARATOR."Views" ;
            $coreBaseViewFiles = scandir($modulesParentDirFullPath);
            $coreHelpViewFiles = scandir($modulesParentHelpDirFullPath);
            foreach ($coreBaseViewFiles as $coreViewFile) {
                if (!in_array($coreViewFile, array(".", ".."))) { // if not dot or double dot
                    $fileNameAndPath = $modulesParentDirFullPath . DIRECTORY_SEPARATOR . $coreViewFile;
                    if (is_readable($fileNameAndPath) && $viewFileName == $coreViewFile) {
                        // @todo require in exception
                        require $fileNameAndPath;
                        return true; } } }
            foreach ($coreHelpViewFiles as $coreViewFile) {
                if (!in_array($coreViewFile, array(".", ".."))) { // if not dot or double dot
                    $fileNameAndPath = $modulesParentHelpDirFullPath . DIRECTORY_SEPARATOR . $coreViewFile;
                    if (is_readable($fileNameAndPath) && $viewFileName == $coreViewFile) {
                        // @todo require in exception
                        require $fileNameAndPath;
                        return true; } } }

        }
    }
    return false;
  }

}