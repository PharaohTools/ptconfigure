<?php

Namespace Model;

class BoxifyUbuntu extends Base {

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

    public function askWhetherToBoxify() {
        if ($this->askToScreenWhetherToBoxify() != true) { return false; }
        $this->getEnvironments() ;
        if ($this->askToScreenWhetherToEditExisting() == true) {
            for ($i=0; $i<count($this->environments); $i++) {

            }
        }

//      1) Do a Boxify?  DONE
//      2) Edit Existing Enviroments?  DONE
//          - If Yes then foreach environment
//            - Remove Boxes
//              All
//              Individual By ID
//             - Add Boxes
//              How Many
//              Provider
//              Type
//              Key/Passwd ID

//      below not necessary
//      3) Add New Environment?
//            - Add Boxes
//         How Many
//         Provider
//         Type
//         Key/Passwd ID



        $this->doBoxify() ;
        return true;
    }

    public function askToScreenWhetherToBoxify() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Boxify This?';
        return self::askYesOrNo($question, true);
    }

    public function askToScreenWhetherToEditExisting() {
        if (isset($this->params["edit-existing"]) && $this->params["edit-existing"]==true) { return true ; }
        $question = 'Edit Existing Environments?';
        return self::askYesOrNo($question, true);
    }

    public function setEnvironmentReplacements() {
        $this->environmentReplacements =
            array( "servers" => array(
                // array("var"=>"dap_proj_cont_dir", "friendly_text"=>"Project Container directory, (inc slash)"),
            ) );
    }

    public function getEnvironments() {
        $envs = AppConfig::getProjectVariable("environments");
        $this->environments = $envs ;
        var_dump($this->environments) ;
        die() ;
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

    private function doBoxify() {
      $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
      $templates = scandir($templatesDir);
      foreach ($this->environments as $environment) {
        foreach ($templates as $template) {
          if (!in_array($template, array(".", ".."))) {
              $templatorFactory = new \Model\Templating();
              $templator = $templatorFactory->getModel($this->params);
              $newFileName = str_replace("environment", $environment["any-app"]["gen_env_name"], $template ) ;
              $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'cleopatra'.DIRECTORY_SEPARATOR.'autopilots';
              $targetLocation = $autosDir.DIRECTORY_SEPARATOR.$newFileName ;
              $templator->template(
                  file_get_contents($templatesDir.DIRECTORY_SEPARATOR.$template),
                  array( "gen_srv_array_text" => $this->getServerArrayText($environment["servers"]) ),
                  $targetLocation ); } } }
    }

}