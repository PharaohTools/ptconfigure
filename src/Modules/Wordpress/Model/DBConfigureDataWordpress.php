<?php

Namespace Model;

class DBConfigureDataWordpress extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default", "WordpressConfig") ;

    private $friendlyName = 'Wordpress';
    private $shortName = 'Wordpress';
    private $settingsFileLocation = ''; // no trail slash, empty for root
    private $settingsFileName = 'src/wp-config.php';
    private $settingsFileReplacements ;
    private $extraConfigFileReplacements ;
    private $extraConfigFiles = array() ; // array('build/config/phpunit/bootstrap.php'); // extra files requiring db config

    public function __construct(){
        $this->setReplacements();
        $this->setExtraConfigReplacements();
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function __call($var1, $var2){
        return null();
    }

    private function setReplacements(){
        $this->settingsFileReplacements = array(
            "DB_NAME" => '  define(\'DB_NAME\', \'****DB NAME****\');',
            'DB_USER' => '  define(\'DB_USER\', \'****DB USER****\');',
            'DB_PASSWORD' => '  define(\'DB_PASSWORD\', \'****DB PASS****\');',
            'DB_HOST' => '  define(\'DB_HOST\', \'****DB HOST****\');',
        );
    }

    private function setExtraConfigReplacements(){
        $this->extraConfigFileReplacements = array(
            '$bootstrapDbName =' => '$bootstrapDbName = "****DB NAME****" ; ',
            '$bootstrapDbUser =' => '$this->dbUser = "****DB USER****" ; ',
            '$bootstrapDbPass =' => '$this->dbPass = "****DB PASS****" ; ',
            '$bootstrapDbHost =' => '$this->dbHost = "****DB HOST****" ; ',
        );
    }

}
