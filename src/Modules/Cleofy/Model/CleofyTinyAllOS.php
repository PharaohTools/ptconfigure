<?php

Namespace Model;

// @todo shouldnt this extend base templater? is it missing anything?
class CleofyTinyAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Tiny") ;

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
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
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
            $serversText .= ' "target" => "'.$serverArray["target"].'", ';
            $serversText .= ' "user" => "'.$serverArray["user"].'", ';
            $serversText .= ' "pword" => "'.$serverArray["password"].'", ';
            $serversText .= '),'."\n"; }
        return $serversText;
    }

    private function doCleofy() {
        $templatesDir = str_replace("Model", "Templates".DS."EnvSpecific", dirname(__FILE__) ) ;
        $templates = scandir($templatesDir);
        $results = array();
        foreach ($this->environments as $environment) {
            foreach ($templates as $template) {
                if (!in_array($template, array(".", ".."))) {
                    $templatorFactory = new \Model\Templating();
                    $templator = $templatorFactory->getModel($this->params);
                    $newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
                    $autosDir = getcwd().'build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy';
                    $targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
                    $results[] = $templator->template(
                        file_get_contents($templatesDir.DIRECTORY_SEPARATOR.$template),
                        array(
                            "gen_srv_array_text" => $this->getServerArrayText($environment["servers"]) ,
                            "env_name" => $environment["any-app"]["gen_env_name"],
                            "first_server_target" => $environment["servers"][0]["target"],
                        ),
                        $targetLocation ); } } }
        $result = (in_array($results, false)) ? false : true ;
        return $result ;
    }

    private function doCleofyGuess() {
        $templatesDir = str_replace("Model", "Templates".DS."EnvSpecific", dirname(__FILE__) ) ;
        $templates = scandir($templatesDir);
        $results = array();


        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if ($this->params["guess"]==true) {
            $logging->log("Attempting to guess settings file", $this->getModuleName()) ;
            $default_settings = getcwd().DS.'build'.DS.'config'.DS.'ptconfigure'.DS.'settings.php' ;
            $exists = file_exists($default_settings) ;
            if ($exists==true) {
                $logging->log("Found settings file $default_settings", $this->getModuleName()) ;
                require_once($default_settings); } }

        if (!isset($bastion_env)) $bastion_env = $this->getEnvName("bastion") ;
        if (!isset($build_env)) $build_env = $this->getEnvName("build") ;
        if (!isset($git_env)) $git_env = $this->getEnvName("git") ;
        if (!isset($staging_env)) $staging_env = $this->getEnvName("staging") ;
        if (!isset($production_env)) $production_env = $this->getEnvName("production") ;


        $result = (in_array($results, false)) ? false : true ;
        return $result ;
    }



    private function getEnvName($env) {
        if (isset($this->params["{$env}-env"])) { return $this->params["{$env}-env"] ; }
        $question = 'Env name for '.ucfirst($env).'?';
        return self::askYesOrNo($question, true);
    }

}