<?php

Namespace Model;

class Index extends Base {

  public function findAllModuleNames(){
    $allInfoObjects = \Core\AutoLoader::getInfoObjects() ;
    $moduleNames = array();
    foreach ($allInfoObjects as $infoObject) {
        $array_keys = array_keys($infoObject->routesAvailable()) ;
        $miniRay = array();
        $miniRay["command"] = $array_keys[0];
        $miniRay["name"] = $infoObject->name ;
        $miniRay["hidden"] = $infoObject->hidden ;
        $moduleNames[] = $miniRay; }
    return $moduleNames;
  }

}