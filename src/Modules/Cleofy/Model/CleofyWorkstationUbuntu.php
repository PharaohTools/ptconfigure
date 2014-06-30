<?php

Namespace Model;

// @todo shouldnt this extend base templater? is it missing anything?
class CleofyWorkstationUbuntu extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("32", "64") ;

    // Model Group
    public $modelGroup = array("Workstation") ;

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

    private function doCleofy() {
      $templatesDir = str_replace("Model", "Templates/Workstation", dirname(__FILE__) ) ;
      $templates = scandir($templatesDir);
      foreach ($this->environments as $environment) {
		if (isset($this->params["environment-name"]) && ($environment["any-app"]["gen_env_name"] == $this->params["environment-name"] ) ) {
			foreach ($templates as $template) {
				if (!in_array($template, array(".", ".."))) {
					$templatorFactory = new \Model\Templating();
					$templator = $templatorFactory->getModel($this->params);
					$newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
					$autosDir = getcwd().'/build/config/cleopatra/cleofy/autopilots/generated';
					$targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
					$templator->template(
					  file_get_contents($templatesDir.DIRECTORY_SEPARATOR.$template),
					  array(
						  "env_name" => $environment["any-app"]["gen_env_name"],
					  ),
					  $targetLocation );
				echo $targetLocation."\n"; } } } }
    }

}
