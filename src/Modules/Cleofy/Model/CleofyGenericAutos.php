<?php

Namespace Model;

class CleofyGenericAutos extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("GenericAutos") ;

    protected $templateGroup;
    protected $templateGroupsToDirs;
    protected $destination;

    protected $actionsToMethods =
        array(
            "install-generic-autopilots" => "performGenericAutopilotInstall",
            "gen" => "performGenericAutopilotInstall",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Cleofy";
        $this->programNameMachine = "cleofy"; // command and app dir name
        $this->programNameFriendly = "Cleofy!"; // 12 chars
        $this->programNameInstaller = "Cleofy your Environments";
        $this->initialize();
    }

    public function performGenericAutopilotInstall() {
        $this->setTemplateGroupsToDirs();
        $this->setTemplateGroup();
        $this->setDestination();
        return $this->doCopy() ;
    }

    public function setTemplateGroupsToDirs() {
        $dir = str_replace("Model", "", __DIR__) ;
        $dir = $dir.'Templates'.DS ;
        $this->templateGroupsToDirs = array(
            "tiny" => "{$dir}Generic".DS."Tiny",
            "medium" => "{$dir}Generic".DS."Medium",
            "dbcluster" => "{$dir}Generic".DS."DBCluster",
            "db-cluster" => "{$dir}Generic".DS."DBCluster",
            "workstation" => "{$dir}Generic".DS."Workstation",
            "ptvirtualize" => "{$dir}Generic".DS."PTVirtualize"
        );
    }

    public function setTemplateGroup($templateGroup = null) {
        // @this should log that you  have specified an invalid template group if that is the case and go to prompt
        if (isset($templateGroup)) {
            $this->templateGroup = $templateGroup; }
        else if (isset($this->params["group"])) {
            $this->templateGroup = $this->params["group"]; }
        else if (isset($this->params["templategroup"])) {
            $this->templateGroup = $this->params["templategroup"]; }
        else if (isset($this->params["template-group"])) {
            $this->templateGroup = $this->params["template-group"]; }
        else {
            $tgs = array_keys($this->templateGroupsToDirs) ;
            $this->templateGroup = self::askForArrayOption("Enter Template Group:", $tgs, true) ; }
    }

    // @todo generic is not the  right word probably
    public function setDestination($destination = null) {
        // @this should log that you have specified an invalid destination if that is the case and go to prompt
        if (isset($destination)) {
            $this->destination = $destination; }
        else if (isset($this->params["destinationdir"])) {
            $this->destination = $this->params["destinationdir"]; }
        else if (isset($this->params["destination-dir"])) {
            $this->destination = $this->params["destination-dir"]; }
        else if (isset($this->params["guess"])) {
            $defaultdir = getcwd().DS."build".DS."config".DS."ptconfigure".DS."cleofy".DS."autopilots".DS."generic" ;
            if (!file_exists($defaultdir)) { mkdir($defaultdir, 0777, true) ; }  ;
            $this->destination = $defaultdir ; }
        else {
            $this->destination = self::askForInput("Enter Destination Directory:", true); }
    }

    protected function doCopy() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $source = $this->templateGroupsToDirs[$this->templateGroup] ;
        $target = $this->destination ;
        $logging->log("Performing file copy from $source to $target", $this->getModuleName()) ;
        $templates = array_diff(scandir($source), array(".", "..") );
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);
        $results = array();
        foreach ($templates as $template) {
            if ($template=="settings.php") {
                // only overwrite settings file if required
                $autosDir = getcwd().DS.'build'.DS.'config'.DS.'ptconfigure' ; }
            else {
                $autosDir = getcwd().DS.'build'.DS.'config'.DS.'ptconfigure'.DS.'cleofy' ; }
            $targetLocation = $autosDir.DS.$template ;
            $results[] = $templator->template(
                file_get_contents($source.DS.$template),
                array(),
                $targetLocation ); }
        $result = (in_array($results, false)) ? false : true ;
        return $result ;
    }

}