<?php

Namespace Model;

class EnvironmentConfig extends BaseModelFactory {

    public static function getModel($params, $moduleType="Listing") {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, $moduleType, $params);
        return $model;
    }

}