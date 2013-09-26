<?php

Namespace Model;

class Dapperfy extends Base {

    private $environments ;
    private $environmentReplacements ;

    public function askWhetherToDapperfy() {
        if ($this->askToScreenWhetherToDapperfy() != true) { return false; }
        $this->setEnvironmentReplacements() ;
        $this->getEnvironments() ;
        $this->doDapperfy() ;
        return true;
    }

    public function askToScreenWhetherToDapperfy() {
      $question = 'Dapperfy This?';
      return self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements() {

      $this->environmentReplacements =
          array( "dapper" => array(
              array("var"=>"dap_proj_cont_dir", "friendly_text"=>"Project Container directory, (inc slash)"),
              array("var"=>"dap_git_repo_url", "friendly_text"=>"Git Repo URL"),
              array("var"=>"dap_git_custom_branch", "friendly_text"=>"Git Custom Branch"),
              array("var"=>"dap_apache_vhost_url", "friendly_text"=>"Apache VHost URL (Don't Include http://)"),
              array("var"=>"dap_apache_vhost_ip", "friendly_text"=>"Apache VHost Hostname/IP"),
              array("var"=>"dap_version_num_revisions", "friendly_text"=>"How many revisions to keep"),
              array("var"=>"dap_db_platform", "friendly_text"=>"DB Platform"),
              array("var"=>"dap_db_ip_address", "friendly_text"=>"DB IP Address"),
              array("var"=>"dap_db_app_user_name", "friendly_text"=>"DB App User Name (Will be created if not existing)"),
              array("var"=>"dap_db_app_user_pass", "friendly_text"=>"DB App User Pass"),
              array("var"=>"dap_db_name", "friendly_text"=>"DB Name (Will be created if not existing)"),
              array("var"=>"dap_db_admin_user_name", "friendly_text"=>"DB Admin User Name"),
              array("var"=>"dap_db_admin_user_pass", "friendly_text"=>"DB Admin User Pass"),
          ) );

    }

    public function getEnvironments() {
        $environmentConfigModel = new EnvironmentConfig();
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

    private function doDapperfy() {
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
      $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'dapperstrano'.DIRECTORY_SEPARATOR.'autopilots';
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