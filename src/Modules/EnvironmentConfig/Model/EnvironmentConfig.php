<?php

Namespace Model;

class EnvironmentConfig extends BaseModelFactory {

    public static function getModel($params) {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, "Listing", $params);
        return $model;
    }

}