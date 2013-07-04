<?php

Namespace Model;

class Index extends Base {

  public function findAllModuleNames(){

    $allInfoObjects = AutoLoader::getInfoObjects() ;
    /*
    $aliases = array("co"=>"checkout", "hosteditor"=>"hostEditor", "he"=>"hostEditor", "host"=>"hostEditor",
        "vhostEditor"=>"VHostEditor", "vhosteditor"=>"VHostEditor", "vhc"=>"VHostEditor", "cuke"=>"cukeConf",
        "cukeconf"=>"cukeConf", "proj"=>"project", "db"=>"database");
    */
    $aliases = array();
    foreach ($allInfoObjects as $infoObject) {
        $aliases = array_merge( $aliases, $infoObject->routeAliases() ); }



    $array_keys = array_keys($infoObject->routesAvailable()) ;
    $miniRay = array();
    $miniRay["command"] = $array_keys[0];
    $miniRay["name"] = $infoObject->name ;

    return $this->performDBDrop();
  }

}