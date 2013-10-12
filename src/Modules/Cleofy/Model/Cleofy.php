<?php

Namespace Model;

class Cleofy {

    public static function getModel($params) {
        $model = \Model\SystemDetectionFactory::getCompatibleModel("Cleofy", "Installer", $params);
        return $model;
    }

}