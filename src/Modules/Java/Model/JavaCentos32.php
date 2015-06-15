<?php

Namespace Model;

class JavaCentos32 extends JavaUbuntu32 {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("32") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->versionRecommendedCommand = "sudo yum info policy java" ;
        $this->versionLatestCommand = "sudo yum info policy java" ;
        $this->initialize();
    }

}
