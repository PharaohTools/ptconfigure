<?php

Namespace Model;

class DBInstallHooksDrupal extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("DrupalDBIHooks") ;

    protected $url ;

    public function __construct() {
    }

    public function preInstallHook($dbiObject){
    }

    public function postInstallHook($dbiObject) {
        $this->url = $this->askForUrl($dbiObject) ;
        $this->wpOptionsUpdater($dbiObject) ;
    }

    // @todo mailserver_*, blogname , blogdescription
    protected function askForUrl($dbiObject){
        if (isset($dbiObject->params["hook-url"])) { return $dbiObject->params["hook-url"] ; }
        $question = 'What is the URL of this site for Drupal Variables? ';
        $input = self::askForInput($question, true);
        return $input ;
    }

    // @todo dont use print, use logging or something better
    // @todo site_mail
    protected function wpOptionsUpdater($dbiObject) {
        $dbc = mysqli_connect($dbiObject->dbHost, $dbiObject->dbRootUser, $dbiObject->dbRootPass);
        echo (mysqli_error($dbc));
        $query = 'UPDATE '.$dbiObject->dbName.'.variable SET value="'.$this->url.'" WHERE name="site_name";';
        mysqli_query($dbc, $query) ;
        print "$query\n";
        echo (mysqli_error($dbc));
        print "Drupal variable site_name updated\n";
    }

}
