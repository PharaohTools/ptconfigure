<?php

Namespace Model;

class DapperfyAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $environments ;
    public $environmentReplacements ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function askWhetherToDapperfy() {
        if ($this->askToScreenWhetherToDapperfy() != true) { return false; }
        $this->setEnvironmentReplacements() ;
        $this->getEnvironments() ;
        $this->doDapperfy() ;
        return true;
    }

    public function askToScreenWhetherToDapperfy() {
        if (isset($this->params["yes"])) { return true ; }
        $question = 'Dapperfy This?';
        return self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements() {

      $this->environmentReplacements =
          array( "ptdeploy" => array(
              array("var"=>"dap_proj_cont_dir", "friendly_text"=>"Project Container directory, (inc slash)"),
              array("var"=>"dap_git_repo_url", "friendly_text"=>"Git Repo URL"),
              array("var"=>"dap_git_repo_ssh_key", "friendly_text"=>"Optional Private SSH Key for Git Repo"),
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
        $environmentConfigModelFactory = new \Model\EnvironmentConfig() ;
        $environmentConfigModel = $environmentConfigModelFactory->getModel($this->params) ;
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

    public function doDapperfy() {
        $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $templates = scandir($templatesDir);
        foreach ($this->environments as $environment) {
            if (isset($this->params["environment-name"])) {
                if ($this->params["environment-name"] != $environment["any-app"]["gen_env_name"]) {
                    $tx = "Skipping Environment {$environment["any-app"]["gen_env_name"]} " ;
                    $tx .= "as specified Environment is {$this->params["environment-name"]} \n" ;
                    echo $tx;
                    continue ; } }

            $servers = (isset($environment["servers"])) ? $environment["servers"] : array() ;
            $defaultReplacements =
            array(
                "gen_srv_array_text" => $this->getServerArrayText($servers) ,
                "env_name" => $environment["any-app"]["gen_env_name"],
                "gen_env_tmp_dir" => $environment["any-app"]["gen_env_tmp_dir"]
            ) ;

            if (isset($environment["ptdeploy"])) {
                $replacements = array_merge($defaultReplacements, $environment["ptdeploy"]) ; }
            else {
                $replacements = $defaultReplacements ; }

            // There is probably a better way to do this, that can allow preprocessing any of the params
            if (isset($replacements["dap_git_repo_ssh_key"]) && strlen($replacements["dap_git_repo_ssh_key"])>0) {
                $str = '"private-key" => "'.$replacements["dap_git_repo_ssh_key"].'" ';
                $replacements["dap_git_key_string"] = $str ; }
            else {
                $replacements["dap_git_key_string"] = "" ; }

            if (!isset($this->params["no-autopilot-creation"])) {
                foreach ($templates as $template) {
                    if (!in_array($template, array(".", ".."))) {
                        $templatorFactory = new \Model\Templating();
                        $templator = $templatorFactory->getModel($this->params);
                        $newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
                        $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.
                            'ptdeploy'.DIRECTORY_SEPARATOR.'dapperfy'.DIRECTORY_SEPARATOR.'autopilots'.DIRECTORY_SEPARATOR.
                            'generated';
                        $targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
                        $templator->template(
                            file_get_contents($templatesDir.DIRECTORY_SEPARATOR.$template),
                            $replacements,
                            $targetLocation );
                        echo $targetLocation."\n"; } } } }

    }

}