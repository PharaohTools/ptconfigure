<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct($params = null) {
        parent::__construct($params);
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {
        $apache_conf = $this->getApacheHttpdconfLocation();
        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure PHP FPM for Pharaoh Build"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Allow apache user to switch to ptbuild user", ), ), ),
                array ( "File" => array( "should-have-line" => array(
                    "guess" => true,
                    "file" => $apache_conf,
                    "search" => "{$apache_user}    ALL=(ptbuild) NOPASSWD: ALL",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Make the PT Build Settings file writable", ), ), ),
                array ( "Chmod" => array( "path" => array(
                    "path" => PFILESDIR.'ptbuild'.DS.'ptbuild'.DS.'ptbuildvars',
                    "mode" => '0777',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for PHP FPM for Pharaoh Build Complete"),),),

            );

    }

    protected function getApacheHttpdconfLocation() {
        $system = new \Model\SystemDetection();
        $thisSystem = $system->getModel($this->params);
        if (in_array($thisSystem->os, array("Darwin") ) ) {
            $apacheUser = "_www" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Debian") ) ) {
            $apacheUser = "www-data" ; }
        else if ($thisSystem->os == "Linux" && in_array($thisSystem->os, array("Redhat") ) ) {
            $apacheUser = "httpd" ; }
        else {
            $apacheUser = "www-data" ; }
        return $apacheUser ;
    }

}
