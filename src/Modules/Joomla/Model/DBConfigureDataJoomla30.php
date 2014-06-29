<?php

Namespace Model;

class DBConfigureDataJoomla30 extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default", "Joomla30Config") ;

    private $friendlyName = 'Joomla 3.x Series';
    private $shortName = 'Joomla30';
    private $settingsFileLocation = 'src'; // no trail slash, empty for root
    private $settingsFileName = 'configuration.php';
    private $settingsFileReplacements ;
    private $extraConfigFileReplacements ;
    private $extraConfigFiles = array('build/config/phpunit/bootstrap.php'); // extra files requiring db config

    public function __construct(){
		$this->setProperties();
        $this->setReplacements();
        $this->setExtraConfigReplacements();
    }

    protected function setProperties() {
		$prefix = (isset($this->params["parent-path"])) ? $this->params["parent-path"] : "" ;
		$this->settingsFileLocation = (strlen($prefix) > 0) ? $prefix.'/src' : '/src' ; // no trail slash, empty for root	
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return null();
    }

    private function setReplacements(){
        $this->settingsFileReplacements = array(
            'public $db ' => '  public $db = "****DB NAME****";',
            'public $user ' => '  public $user = "****DB USER****";',
            'public $password ' => '  public $password = "****DB PASS****";',
            'public $host ' => '  public $host = "****DB HOST****";');
    }

    private function setExtraConfigReplacements(){
        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$this->dbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$this->dbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$this->dbHost = "****DB HOST****" ; ');
    }

}
