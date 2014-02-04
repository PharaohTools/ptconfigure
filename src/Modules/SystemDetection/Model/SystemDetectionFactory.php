<?php

Namespace Model;

class SystemDetectionFactory {

    public static function getCompatibleModel($module, $modelGroup, $modelParams) {
        $modelsInModuleGroup = self::getModelsInModuleGroup($module, $modelGroup, $modelParams) ;
        $mostCompatibleModel = self::getCompatibleModelFromAllInGroup($modelsInModuleGroup) ;
        return $mostCompatibleModel ;
    }

    public static function getModelsInModuleGroup($module, $modelGroup, $modelParams) {
        $allModelsOfModule = \Core\AutoLoader::getAllModelsOfModule($module, $modelParams);
        $groupModels = array() ;
        foreach ($allModelsOfModule as $modelOfModule) {
            if ( (isset($modelOfModule->modelGroup) && in_array($modelGroup, $modelOfModule->modelGroup) ) ||
                 (isset($modelOfModule->modelGroup) && in_array("any", $modelOfModule->modelGroup) ) ) {
                $groupModels[] = $modelOfModule ; } }
        return $groupModels;
    }

    public static function getCompatibleModelFromAllInGroup($models) {
        include_once("SystemDetectionAllOS.php");
        $system = new \Model\SystemDetectionAllOS();
        foreach($models as $model) {
            if (
                (in_array($system->os, $model->os) || in_array("any", $model->os)) &&
                (in_array($system->linuxType, $model->linuxType) || in_array("any", $model->linuxType)) &&
                (in_array($system->distro, $model->distros) || in_array("any", $model->distros)) &&
                (in_array($system->version, $model->versions) || in_array("any", $model->versions)) &&
                (in_array($system->architecture, $model->architectures) || in_array("any", $model->architectures))
            ) {
                // if the everything matches, we have an exact match so return it
                return $model; }
            else if (
                (in_array($system->os, $model->os) || in_array("any", $model->os)) &&
                (in_array($system->linuxType, $model->linuxType) || in_array("any", $model->linuxType)) &&
                (in_array($system->distro, $model->distros) || in_array("any", $model->distros)) &&
                (in_array($system->architecture, $model->architectures) || in_array("any", $model->architectures))
            ) {
                // if just the distro match, we still return it but with a "might not work as expected"
                // warning during expected models phase
                $message ="Cleopatra Warning!: Model ".get_class($model)." may not work as expected, since it " .
                    "doesn't specify exact OS version match";
                // error_log($message);
                return $model; }
            else if (
                (in_array($system->os, $model->os) || in_array("any", $model->os)) &&
                (in_array($system->linuxType, $model->linuxType) || in_array("any", $model->linuxType)) &&
                (in_array($system->architecture, $model->architectures) || in_array("any", $model->architectures))
            ) {
                // if the OS matches, we still return it but with an extra high level warning
                // during expected models phase
                $message = "Cleopatra Urgent Warning!: Model ".get_class($model)." may not work as expected, since " .
                    "it doesn't specify matching OS version or distro match";
                // error_log($message);
                return $model; } }
        return null ;
    }

}