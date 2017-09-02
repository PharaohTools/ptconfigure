<?php

Namespace Model;

class DBConfigureDataISOPHP extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default", "ISOPHPConfig") ;

    private $friendlyName = 'ISOPHP';
    private $shortName = 'ISOPHP';
    private $settingsFileLocation = ''; // no trail slash, empty for root
    private $settingsFileName = 'configuration.php';
    private $settingsFileReplacements ;
    private $extraConfigFileReplacements ;
    private $extraConfigFiles ; // extra files requiring db config
    public $dbHost;

    public function __construct($params = array()){
        parent::__construct($params) ;
//        $this->extraConfigFiles = array('build'.DS.'config'.DS.'phpunit'.DS.'bootstrap.php');
		$this->setProperties();
        $this->setReplacements();
        $this->setExtraConfigReplacements();
    }

    protected function setProperties() {
        $prefix = (isset($this->params["parent-path"])) ? $this->params["parent-path"] : "";
        if (strlen($prefix) > 0) { $this->settingsFileLocation = ""; }
        else { $this->settingsFileName = 'src'.DS.'configuration.php'; }
    }

    public function getConfigProperty($property) {
        $prefix = (isset($this->params["parent-path"])) ? "" : getcwd() ;
        $jconfloc = $prefix.$this->settingsFileLocation.DS.$this->settingsFileName ;
        include_once($jconfloc) ;
        if (!class_exists("JConfig")) {
            \Core\Bootstrap::setExitCode(1);
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Unable to load ISOPHP Configuration File ", $this->getModuleName());
            return false ; }
        $jconf =  new \JConfig() ;
        $cprops["dbHost"] = $jconf->host ;
        $cprops["dbName"] = $jconf->db ;
        $cprops["dbUser"] = $jconf->user ;
        $cprops["dbPass"] = $jconf->password ;
        return ($cprops[$property]) ? $cprops[$property] : null ;
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return "" ; // @todo what even is this
    }

    private function setReplacements(){
        $this->settingsFileReplacements = array(
            'public $db ' => '  public $db = "****DB NAME****";',
            'public $user ' => '  public $user = "****DB USER****";',
            'public $password ' => '  public $password = "****DB PASS****";',
            'public $host ' => '  public $host = "****DB HOST****";',
        );
    }

    private function setExtraConfigReplacements(){
        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$this->dbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$this->dbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$this->dbHost = "****DB HOST****" ; ');
    }

}
