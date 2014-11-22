<?php

Namespace Model;

class PapyrusEditor extends BaseModelFactory {

    public static function getModel($params, $modGroup = "Editor") {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, $modGroup, $params);
        return $model;
    }

}
