<?php

Namespace Model;

class PapyrusEditor extends BaseModelFactory {

    public static function getModel($params) {
        $thisModule = substr(get_called_class(), 6) ;
        $model = \Model\SystemDetectionFactory::getCompatibleModel($thisModule, "Editor", $params);
        return $model;
    }

}
