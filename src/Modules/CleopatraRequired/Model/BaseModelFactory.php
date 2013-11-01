<?php

Namespace Model;

class BaseModelFactory {

    public static function getModel($params) {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, "Installer", $params);
        return $model;
    }

}