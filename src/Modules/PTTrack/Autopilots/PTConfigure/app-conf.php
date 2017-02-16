<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct($params = null) {
        parent::__construct($params);
        $this->setSteps();
    }

    private function findPackageStep() {


        $systemDetection = new \Model\SystemDetectionAllOS();
        if ($systemDetection->linuxType === 'Debian') {
            $ray =
                array ( "PackageManager" => array( "pkg-install" => array(
                    "package-name" => "sqlite",
                    "packager" => 'Apt',
                ), ), ) ;
            return $ray ;
        }
        else if ($systemDetection->linuxType === 'Redhat') {
            $ray =
                array ( "PackageManager" => array( "pkg-install" => array(
                    "package-name" => "sqlite",
                    "packager" => 'Yum',
                ), ), ) ;
            return $ray ;
        }
        return array() ;
    }

    /* Steps */
    private function setSteps() {

        $package_ray = $this->findPackageStep() ;

        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets configure users and permissions for Pharaoh Track"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Install the SQLLite Package", ), ), ),

                $package_ray,

                array ( "Logging" => array( "log" => array( "log-message" => "Allow user pttrack a passwordless sudo", ), ), ),
                array ( "SudoNoPass" => array( "install" => array(
                    "guess" => true,
                    "install-user-name" => 'pttrack',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Make the PT Track Settings file writable", ), ), ),
                array ( "Chmod" => array( "path" => array(
                    "path" => PFILESDIR.'pttrack'.DS.'pttrack'.DS.'pttrackvars',
                    "mode" => "777",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Ensure the Jobs Directory exists", ), ), ),
                array ( "Mkdir" => array( "path" => array(
                    "path" => PFILESDIR.'pttrack'.DS.'data',
                    "mode" => "755",
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Track Complete"),),),

            );

    }

}
