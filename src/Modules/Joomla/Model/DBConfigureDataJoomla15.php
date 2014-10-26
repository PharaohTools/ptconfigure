<?php

Namespace Model;

class DBConfigureDataJoomla15 extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Joomla15Config") ;

    private $friendlyName = 'Joomla 1.5.x Series';
    private $shortName = 'Joomla15';
    private $settingsFileLocation = ''; // no trail slash, empty for root
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
        if (strlen($prefix) > 0) {
            $this->settingsFileLocation = $prefix; }
        else {
            $this->settingsFileName = 'src/configuration.php'; }
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return "" ; // @todo what even is this
    }

    private function setReplacements(){
        $this->settingsFileReplacements = array(
            'var $db ' => '  var $db = "****DB NAME****";',
            'var $user ' => '  var $user = "****DB USER****";',
            'var $password ' => '  var $password = "****DB PASS****";',
            'var $host ' => '  var $host = "****DB HOST****";');
    }

    private function setExtraConfigReplacements(){
        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$this->dbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$this->dbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$this->dbHost = "****DB HOST****" ; ');
    }

}