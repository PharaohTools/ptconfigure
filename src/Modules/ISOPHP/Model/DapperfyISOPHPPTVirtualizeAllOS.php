<?php

Namespace Model;

class DapperfyJoomlaPTVirtualizeAllOS extends DapperfyAllOS {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("DapperfyJoomlaPTVirtualize") ;

    public $platform = "joomla30" ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function askToScreenWhetherToDapperfy() {
        if (isset($this->params["yes"])) { return true ; }
        $question = 'Dapperfy This for Joomla on PTVirtualize?';
        return self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements() {

        /* @todo use some logic to get the value set by parent::setEnvironmentReplacements()
         * and just unset the dap_db_platform var
         *
         * @todo removing the ones that we are not using from the pool so questions are not even asked
        */
        $this->environmentReplacements =
          array( "ptdeploy" => array(
              array("var"=>"dap_proj_cont_dir", "friendly_text"=>"Project Container directory, (inc slash)"),
              array("var"=>"dap_git_repo_url", "friendly_text"=>"Git Repo URL"),
              array("var"=>"dap_git_repo_ssh_key", "friendly_text"=>"Optional Private SSH Key for Git Repo"),
              array("var"=>"dap_git_custom_branch", "friendly_text"=>"Git Custom Branch"),
              array("var"=>"dap_apache_vhost_url", "friendly_text"=>"Apache VHost URL (Don't Include http://)"),
          ) );

    }


    public function doDapperfy() {

        $templatesDir2 = str_replace("Model", "Templates/Dapperfy/Joomla30PTVirtualize", dirname(__FILE__) ) ;
        $templates2 = scandir($templatesDir2);

        foreach ($this->environments as $environment) {

            if (isset($this->params["environment-name"])) {
                if ($this->params["environment-name"] != $environment["any-app"]["gen_env_name"]) {
                    $tx = "Skipping Environment {$environment["any-app"]["gen_env_name"]} to create files " ;
                    $tx .= "as specified Environment is {$this->params["environment-name"]} \n" ;
                    echo $tx;
                    continue ; } }

            $servers = (isset($environment["servers"])) ? $environment["servers"] : array() ;

            $defaultReplacements =
                array(
                    "gen_srv_array_text" => $this->getServerArrayText($servers) ,
                    "env_name" => $environment["any-app"]["gen_env_name"],
                    "dap_db_platform" => $this->platform,
                    "gen_env_tmp_dir" => $environment["any-app"]["gen_env_tmp_dir"],
                    "dap_db_ip_address" => "127.0.0.1",
                    "dap_db_app_user_name" => "ph_user",
                    "dap_db_app_user_pass" => "ph_pass",
                    "dap_db_name" => "ph_db",
                    "dap_db_admin_user_name" => "root",
                    "dap_db_admin_user_pass" => "ptconfigure",
                    "dap_apache_vhost_ip" => "0.0.0.0"
                ) ;

            if (isset($environment["ptdeploy"])) {
                $replacements = array_merge($defaultReplacements, $environment["ptdeploy"]) ; }
            else {
                $replacements = $defaultReplacements ; }

            if (!isset($this->params["no-autopilot-creation"])) {

                echo "Joomla Dapperfies for PTVirtualize:\n" ;
                foreach ($templates2 as $template) {
                    if (!in_array($template, array(".", ".."))) {
                        $templatorFactory = new \Model\Templating();
                        $templator = $templatorFactory->getModel($this->params);
                        $newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
                        $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.
                            'ptdeploy'.DIRECTORY_SEPARATOR.'dapperfy'.DIRECTORY_SEPARATOR.'autopilots'.DIRECTORY_SEPARATOR.
                            'generated';
                        $targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
                        $templator->template(
                            file_get_contents($templatesDir2.DIRECTORY_SEPARATOR.$template),
                            $replacements,
                            $targetLocation );
                        echo $targetLocation."\n"; } } }

            else {
                echo "Skipping creation of autopilot files in environment {$environment["any-app"]["gen_env_name"]} due to no-autopilot-creation parameter.\n" ; } }

    }
}