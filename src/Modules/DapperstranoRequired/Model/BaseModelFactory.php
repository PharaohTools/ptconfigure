<?php

Namespace Model;

class BaseModelFactory {

    public static function getModel($params, $modelGroup="Installer") {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, $modelGroup, $params);
        return $model;
    }

}