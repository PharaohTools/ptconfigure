<?php

Namespace Model;

class DapperfyWordpressAllOS extends DapperfyAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("DapperfyWordpress") ;

    public $platform = "wordpress30" ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function askToScreenWhetherToDapperfy() {
        if (isset($this->params["yes"])) { return true ; }
        $question = 'Dapperfy This for Wordpress?';
        return self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements() {

        /* @todo use some logic to get the value set by parent::setEnvironmentReplacements()
         * and just unset the dap_db_platform var
        */
        $this->environmentReplacements =
          array( "dapper" => array(
              array("var"=>"dap_proj_cont_dir", "friendly_text"=>"Project Container directory, (inc slash)"),
              array("var"=>"dap_git_repo_url", "friendly_text"=>"Git Repo URL"),
              array("var"=>"dap_git_repo_ssh_key", "friendly_text"=>"Optional Private SSH Key for Git Repo"),
              array("var"=>"dap_git_custom_branch", "friendly_text"=>"Git Custom Branch"),
              array("var"=>"dap_apache_vhost_url", "friendly_text"=>"Apache VHost URL (Don't Include http://)"),
              array("var"=>"dap_apache_vhost_ip", "friendly_text"=>"Apache VHost Hostname/IP"),
              array("var"=>"dap_version_num_revisions", "friendly_text"=>"How many revisions to keep"),
              array("var"=>"dap_db_ip_address", "friendly_text"=>"DB IP Address"),
              array("var"=>"dap_db_app_user_name", "friendly_text"=>"DB App User Name (Will be created if not existing)"),
              array("var"=>"dap_db_app_user_pass", "friendly_text"=>"DB App User Pass"),
              array("var"=>"dap_db_name", "friendly_text"=>"DB Name (Will be created if not existing)"),
              array("var"=>"dap_db_admin_user_name", "friendly_text"=>"DB Admin User Name"),
              array("var"=>"dap_db_admin_user_pass", "friendly_text"=>"DB Admin User Pass"),
          ) );

    }

    public function doDapperfy() {
        $templatesDir = str_replace("Model", "Templates/Dapperfy/".ucfirst($this->platform), dirname(__FILE__) ) ;
        $templates = scandir($templatesDir);
        foreach ($this->environments as $environment) {

            if (isset($this->params["environment-name"])) {
                if ($this->params["environment-name"] != $environment["any-app"]["gen_env_name"]) {
                    $tx = "Skipping Environment {$environment["any-app"]["gen_env_name"]} to create files " ;
                    $tx .= "as specified Environment is {$this->params["environment-name"]} \n" ;
                    echo $tx;
                    continue ; } }

            $defaultReplacements =
            array(
                "gen_srv_array_text" => $this->getServerArrayText($environment["servers"]) ,
                "env_name" => $environment["any-app"]["gen_env_name"],
                "dap_db_platform" => $this->platform,
                "gen_env_tmp_dir" => $environment["any-app"]["gen_env_tmp_dir"]
            ) ;

            if (isset($environment["dapper"])) {
                $replacements = array_merge($defaultReplacements, $environment["dapper"]) ; }
            else {
                $replacements = $defaultReplacements ; }

            foreach ($templates as $template) {
            if (!in_array($template, array(".", ".."))) {
                $templatorFactory = new \Model\Templating();
                $templator = $templatorFactory->getModel($this->params);
                $newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
                $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'dapperstrano'.DIRECTORY_SEPARATOR.'autopilots';
                $targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
                $templator->template(
                    file_get_contents($templatesDir.DIRECTORY_SEPARATOR.$template),
                    $replacements,
                    $targetLocation );
                echo $targetLocation."\n"; } } }
    }

}