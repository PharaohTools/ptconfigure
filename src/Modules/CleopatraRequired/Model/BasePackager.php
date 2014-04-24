<?php

Namespace Model;

class BasePackager extends BaseLinuxApp {

    protected $packageName ;
    public $actionsToMethods =
        array(
            "pkg-install" => "performInstall",
            "pkg-remove" => "performRemove",
            "pkg-exists" => "performExistenceCheck",
            "update" => "performUpdate",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
    }

    protected function getPackageName($packageName = null) {
        if (isset($packageName)) {  }
        else if (isset($this->params["packagename"])) {
            $packageName = $this->params["packagename"]; }
        else if (isset($this->params["package-name"])) {
            $packageName = $this->params["package-name"]; }
        else if (isset($autopilot["package-name"])) {
            $packageName = $autopilot["package-name"]; }
        else if (isset($autopilot["packagename"])) {
            $packageName = $autopilot["packagename"]; }
        else {
            $packageName = self::askForInput("Enter Package Name:", true); }
        return $packageName ;
    }

    public function setPackage($packageName = null) {
        if (isset($packageName)) {
            $this->packageName = $packageName; }
        else if (isset($this->params["packagename"])) {
            $this->packageName = $this->params["packagename"]; }
        else if (isset($this->params["package-name"])) {
            $this->packageName = $this->params["package-name"]; }
        else if (isset($autopilot["packagename"])) {
            $this->packageName = $autopilot["packagename"]; }
        else if (isset($autopilot["package-name"])) {
            $this->packageName = $autopilot["package-name"]; }
        else {
            $this->packageName = self::askForInput("Enter Package Name:", true); }
    }

    protected function performInstall() {
        $this->setPackage() ;
        $result = $this->installPackage($this->packageName);
        return $result;
    }

    protected function performRemove() {
        $this->setPackage() ;
        $result = $this->removePackage($this->packageName);
        return $result ;
    }

    protected function performUpdate() {
        $result = $this->update();
        return $result ;
    }

    protected function performExistenceCheck() {
        $this->setPackage() ;
        $result = $this->isInstalled($this->packageName);
        return $result ;
    }

}