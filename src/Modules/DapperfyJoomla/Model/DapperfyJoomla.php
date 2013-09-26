<?php

Namespace Model;

class DapperfyJoomla extends Base {

    private $environments ;
    private $replacements ;
    private $possibleReplacements ;

    public function askWhetherToDapperfy() {
        if ($this->askToScreenWhetherToDapperfy() != true) { return false; }
        $this->setPossibleReplacements() ;
        $this->getEnvironments() ;
        $this->getReplacements() ;
        $this->doDapperfy() ;
        return true;
    }

    public function askToScreenWhetherToDapperfy() {
      $question = 'Dapperfy This?';
      return self::askYesOrNo($question, true);
    }

    public function setPossibleReplacements() {
      $this->possibleReplacements = array(
        "****projectContainerDirectory****",
        "****gitCheckoutProjectOriginRepo****",
        "****gitCheckoutCustomBranch****",
        "****virtualHostEditorAdditionURL****",
        "****virtualHostEditorAdditionIp****",
        "****dbPlatform****",
        "****dbIp****",
        "****dbAppUserName****",
        "****dbAppUserPass****",
        "****dbName****",
        "****dbRootUserName****",
        "****dbRootUserPass****",
      );
    }

    public function getEnvironments() {
      $more_servers = true;
      $i = 0 ;
      while ($more_servers == true) {
        if (count($this->environments)==0) {
          echo "Environment Number $i: ";
          $this->environments[$i]["****CURRENT_ENVIRONMENT_NAME****"] = self::askForInput("Value for: Name", true);
          $this->environments[$i]["****CURRENT_ENVIRONMENT_TEMP_DIR****"] = self::askForInput("Value for: Temp Dir", true);
          $this->environments[$i]["****CURRENT_ENVIRONMENT_NUMBER_REVISIONS****"] = self::askForInput("Value for: Number Revisions", true);
          $this->environments[$i]["servers"] = $this->getServers(); }
        else {
          $question = 'Do you want to add another environment?';
          $add_another_server = self::askYesOrNo($question);
          if ($add_another_server == true) {
            echo "Environment Number $i: ";
            $this->environments[$i]["****CURRENT_ENVIRONMENT_NAME****"] = self::askForInput("Value for: Name", true);
            $this->environments[$i]["****CURRENT_ENVIRONMENT_TEMP_DIR****"] = self::askForInput("Value for: Temp Dir", true);
            $this->environments[$i]["****CURRENT_ENVIRONMENT_NUMBER_REVISIONS****"] = self::askForInput("Value for: Number Revisions", true);
            $this->environments[$i]["servers"] = $this->getServers(); }
          else {
            $more_servers = false; } }
        $i++; }
    }



    public function getReplacements() {
      foreach ($this->possibleReplacements as $replacementQuestion) {
        $this->replacements[$replacementQuestion] = self::askForInput("Value for: ".$replacementQuestion); }
    }

    private function doDapperfy() {
      $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
      $templates = scandir($templatesDir);
      foreach ($this->environments as $environment) {
        foreach ($templates as $template) {
          if (!in_array($template, array(".", ".."))) {
            $fileData = $this->loadFile($templatesDir.DIRECTORY_SEPARATOR.$template);
            $fileData = $this->dataChange($fileData, $environment);
            $this->saveFile($template, $environment["****CURRENT_ENVIRONMENT_NAME****"], $fileData); } } }
    }

    private function loadFile($fileToLoad) {
      $command = 'cat '.$fileToLoad;
      $fileData = self::executeAndLoad($command);
      return $fileData ;
    }

    private function saveFile($fileName, $environmentName, $fileData) {
      $newFileName = str_replace("environment", $environmentName, $fileName ) ;
      $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'dapperstrano'.DIRECTORY_SEPARATOR.'autopilots-testing';
      if (!file_exists($autosDir)) { mkdir ($autosDir, 0777, true); }
      return file_put_contents($autosDir.DIRECTORY_SEPARATOR.$newFileName, $fileData);
    }

    private function dataChange($fileData, $environment){
      $newFileData = strtr($fileData, $environment);
      $newFileData = strtr($newFileData, $this->replacements);
      return $newFileData ;
    }



}