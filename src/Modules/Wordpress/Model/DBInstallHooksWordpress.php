<?php

Namespace Model;

class DBInstallHooksWordpress extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("WordpressDBIHooks") ;

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
        $question = 'What is the URL of this site for WP Options? ';
        $input = self::askForInput($question, true);
        return $input ;
    }

    // @todo dont use print, use logging or something better
    protected function wpOptionsUpdater($dbiObject) {
        $dbc = mysqli_connect($dbiObject->dbHost, $dbiObject->dbRootUser, $dbiObject->dbRootPass);
        echo (mysqli_error($dbc));
        $query = 'UPDATE '.$dbiObject->dbName.'.wp_options SET option_value="http://'.$this->url.'" WHERE option_name="siteurl";';
        mysqli_query($dbc, $query) ;
        print "$query\n";
        echo (mysqli_error($dbc));
        print "Wordpress option siteurl updated\n";
        $query = 'UPDATE '.$dbiObject->dbName.'.wp_options SET option_value="http://'.$this->url.'" WHERE option_name="home";';
        mysqli_query($dbc, $query) ;
        print "$query\n";
        echo (mysqli_error($dbc));
        print "Wordpress option home updated\n";
    }

}
