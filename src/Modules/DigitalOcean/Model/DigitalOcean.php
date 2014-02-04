<?php

Namespace Model;

class DigitalOcean extends BaseModelFactory {

    public static function getModel($params) {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, "Base", $params);
        return $model;
    }

}