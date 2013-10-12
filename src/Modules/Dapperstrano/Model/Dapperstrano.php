<?php

Namespace Model;

class Dapperstrano extends BasePHPApp {

    public static function getModel($params) {
        $model = \Model\SystemDetectionFactory::getCompatibleModel("Dapperstrano", "Installer", $params);
        return $model;
    }

}