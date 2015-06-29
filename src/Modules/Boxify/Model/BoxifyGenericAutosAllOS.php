<?php

Namespace Model;

class BoxifyGenericAutosAllOS extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Boxify";
        $this->programNameMachine = "boxify"; // command and app dir name
        $this->programNameFriendly = "Boxify!"; // 12 chars
        $this->programNameInstaller = "Boxify your Environments";
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
        $dir = $dir.'Autopilots'.DS ;
        $this->templateGroupsToDirs = array(
            "tiny" => "{$dir}Tiny",
            "medium" => "{$dir}Medium",
            "dbcluster" => "{$dir}DBCluster",
            "db-cluster" => "{$dir}DBCluster",
        );
    }

    public function setTemplateGroup($templateGroup = null) {
        if (isset($templateGroup)) {
            $this->templateGroup = $templateGroup; }
        else if (isset($this->params["group"])) {
            $this->templateGroup = $this->params["group"]; }
        else if (isset($this->params["templategroup"])) {
            $this->templateGroup = $this->params["templategroup"]; }
        else if (isset($this->params["template-group"])) {
            $this->templateGroup = $this->params["template-group"]; }
        else {
            $options = array("tiny", "medium", "dbcluster", "db-cluster") ;
            $this->templateGroup = self::askForArrayOption("Enter Template Group:", $options, true) ; }
    }

    public function setDestination($destination = null) {
        if (isset($destination)) {
            $this->destination = $destination; }
        else if (isset($this->params["destinationdir"])) {
            $this->destination = $this->params["destinationdir"]; }
        else if (isset($this->params["destination-dir"])) {
            $this->destination = $this->params["destination-dir"]; }
        else if (isset($this->params["guess"])) {
            $defaultdir = getcwd().DS."build".DS."config".DS."ptconfigure".DS."boxify".DS."autopilots".DS;
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
                $autosDir = getcwd().DS.'build'.DS.'config'.DS.'ptconfigure' ; }
            else {
                $autosDir = getcwd().DS.'build'.DS.'config'.DS.'ptconfigure'.DS.'boxify' ; }
            $targetLocation = $autosDir.DS.$template ;
            $results[] = $templator->template(
                file_get_contents($source.DS.$template),
                array(),
                $targetLocation ); }
        $result = (in_array($results, false)) ? false : true ;
        return $result ;
    }

}