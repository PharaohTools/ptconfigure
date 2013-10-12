<?php

Namespace Model;

class ApacheServer {

    public static function getModel($params) {
        $model = \Model\SystemDetectionFactory::getCompatibleModel("ApacheServer", "Installer", $params);
        return $model;
    }

}