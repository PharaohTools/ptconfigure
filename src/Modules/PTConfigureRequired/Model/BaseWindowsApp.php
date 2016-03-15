<?php

Namespace Model;

class BaseWindowsApp extends BaseLinuxApp {

    public $defaultStatusCommandPrefix = "where.exe";

    public function __construct($params) {
        parent::__construct($params);
    }

    //@todo maybe this should be a helper
    public function packageAdd($packager, $package, $version = null, $versionOperator = "+") {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageEnsure($packager, $package, $this, $version, $versionOperator);
    }

    //@todo maybe this should be a helper
    public function packageRemove($packager, $package) {
        $packageFactory = new PackageManager();
        $packageManager = $packageFactory->getModel($this->params) ;
        $packageManager->performPackageRemove($packager, $package, $this);
    }


}