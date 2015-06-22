<?php

Namespace Model;

class CleofyEmpty extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Empty") ;

    protected $templateGroup;
    protected $templateGroupsToDirs;
    protected $path;
    protected $fileName;
    protected $className;
    protected $includeTests ;


    protected $actionsToMethods =
        array(
            "empty" => "performEmptyAutopilotInstall",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Cleofy";
        $this->programNameMachine = "cleofy"; // command and app dir name
        $this->programNameFriendly = "Cleofy!"; // 12 chars
        $this->programNameInstaller = "Cleofy your Environments";
        $this->initialize();
    }

    public function performEmptyAutopilotInstall() {
        $this->setPath();
        $this->setFilename();
        $this->setClassName();
        $this->setIncludeTests();
        $this->setTemplateGroupsToDirs();
        return $this->doTemplating() ;
    }

    public function setTemplateGroupsToDirs() {
        $dir = str_replace("Model", "", __DIR__) ;
        $dir = $dir.'Templates'.DS ;
        $this->templateGroupsToDirs = array(
            "empty" => "{$dir}Generic".DS."Empty",
        );
    }

    public function setPath($path = null) {
        // @this should log that you have specified an invalid path if that is the case and go to prompt
        if (isset($path)) {
            $this->path = $path; }
        else if (isset($this->params["path"])) {
            $this->path = $this->params["path"]; }
        else if (isset($this->params["guess"])) {
            $this->path = getcwd() ; }
        else {
            $this->path = self::askForInput("Enter Save Path:", true); }
    }

    public function setFilename($filename = null) {
        if (isset($fileName)) {
            $this->fileName = $fileName; }
        else if (isset($this->params["fileName"])) {
            $this->fileName = $this->params["fileName"]; }
        else if (isset($this->params["guess"])) {
            $this->fileName = "autopilot.php" ; }
        else {
            $this->fileName = self::askForInput("Enter File Name:", true); }
    }

    public function setClassName($className = null) {
        if (isset($className)) {
            $this->className = $className; }
        else if (isset($this->params["classname"])) {
            $this->className = $this->params["classname"]; }
        else if (isset($this->params["guess"])) {
            $this->className = "AutoPilotConfigured" ; }
        else {
            $this->className = self::askForInput("Enter Class Name:", true); }
    }

    public function setIncludeTests($includeTests = null) {
        if (isset($includeTests)) {
            $this->includeTests = $includeTests; }
        else if (isset($this->params["tests"])) {
            $this->includeTests = $this->params["tests"]; }
        else if (isset($this->params["guess"])) {
            $this->includeTests = true ; }
        else {
            $this->includeTests = self::askYesOrNo("Include Tests?:"); }
    }

    protected function doTemplating() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $source = $this->templateGroupsToDirs["empty"].DS."empty.php" ;
        $templatorFactory = new \Model\Templating();
        $templator = $templatorFactory->getModel($this->params);
        $targetLocation = $this->path.DS.$this->fileName ;
        $tests_property = ($this->includeTests == true) ? $this->getTestsProperty() : "" ;
        $tests_method = ($this->includeTests == true) ? $this->getTestsMethod() : "" ;
        $result = $templator->template(
            file_get_contents($source),
            array(
                "file_name" => $this->fileName ,
                "class_name" => $this->className ,
                "tests_property" => $tests_property,
                "tests_method" => $tests_method, ),
            $targetLocation );
        $logging->log("Cleofied new empty autopilot to $targetLocation", $this->getModuleName()) ;
        return $result ;
    }

    protected function getTestsProperty() {
        return '    public $tests ;';
    }

    protected function getTestsMethod() {
        return 'protected function setTests() {
        $this->tests =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets write a test" ),),),
            );
    }';
    }


}