<?php

Namespace Model;

class CleofyUbuntu extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $environments ;
    private $environmentReplacements ;

    public function __construct($params) {
      parent::__construct($params);
    }

    public function askWhetherToCleofy() {
        if ($this->askToScreenWhetherToCleofy() != true) { return false; }
        $this->setEnvironmentReplacements() ;
        $this->getEnvironments() ;
        $this->doCleofy() ;
        return true;
    }

    public function askToScreenWhetherToCleofy() {
      $question = 'Cleofy This?';
      return self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements() {
        $this->environmentReplacements =
            array( "cleo" => array(
               // array("var"=>"dap_proj_cont_dir", "friendly_text"=>"Project Container directory, (inc slash)"),
            ) );
    }

    public function getEnvironments() {
        $environmentConfigModelFactory = new EnvironmentConfig();
        $environmentConfigModel = $environmentConfigModelFactory->getModel($this->params);
        $environmentConfigModel->askWhetherToEnvironmentConfig($this->environmentReplacements) ;
        $this->environments = $environmentConfigModel->environments ;
    }

    public function getServerArrayText($serversArray) {
        $serversText = "";
        foreach($serversArray as $serverArray) {
            $serversText .= 'array(';
            $serversText .= '"target" => "'.$serverArray["target"].'", ';
            $serversText .= '"user" => "'.$serverArray["user"].'", ';
            $serversText .= '"pword" => "'.$serverArray["password"].'", ';
            $serversText .= '),'."\n"; }
        return $serversText;
    }

    private function doCleofy() {
      $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
      $templates = scandir($templatesDir);
      foreach ($this->environments as $environment) {
        foreach ($templates as $template) {
          if (!in_array($template, array(".", ".."))) {
            $fileData = $this->loadFile($templatesDir.DIRECTORY_SEPARATOR.$template);
            $fileData = $this->dataChange($fileData, $environment);
            $this->saveFile($template, $environment["any-app"]["gen_env_name"], $fileData); } } }
    }

    private function loadFile($fileToLoad) {
      $command = 'cat '.$fileToLoad;
      $fileData = self::executeAndLoad($command);
      return $fileData ;
    }

    private function saveFile($fileName, $environmentName, $fileData) {
      $newFileName = str_replace("environment", $environmentName, $fileName ) ;
      $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'cleopatra'.DIRECTORY_SEPARATOR.'autopilots';
      if (!file_exists($autosDir)) { mkdir ($autosDir, 0777, true); }
      if (!file_exists(getcwd().DIRECTORY_SEPARATOR.'src')) { mkdir (getcwd().DIRECTORY_SEPARATOR.'src', 0777, true); }
      return file_put_contents($autosDir.DIRECTORY_SEPARATOR.$newFileName, $fileData);
    }

    private function dataChange($fileData, $environment){
        foreach ($environment as $var_group_key => $var_group_val) {
            foreach ($var_group_val as $var_key => $var_val) {
                if (is_array($var_val) && $var_group_key=="servers") {
                    $fileData = str_replace('****gen_srv_array_text****', $this->getServerArrayText($var_group_val), $fileData); }
                else {
                    $fileData = str_replace('****'.$var_key.'****', $var_val, $fileData);  } } }
        return $fileData ;
    }

}