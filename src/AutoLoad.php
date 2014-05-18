<?php

Namespace Core;

class AutoLoader{

    public function launch() {
        spl_autoload_register('Core\autoLoader::autoLoad');
    }

    public static function autoLoad($className) {
        $classNameForLoad = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $classNameForLoad.'.php';
        if (is_readable($filename)) {
            require_once $filename;
            return; }
        // look in Extensions, and then in core modules, so that an extension version will load first
        // also finally looks in Core, but only Base should be in there
        $allModuleParentDirectories = array("Extensions", "Modules", "Core");
        foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
            $currentModulesParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
            $modulesIndividualDirectories = scandir($currentModulesParentDir);
            foreach ($modulesIndividualDirectories as $singleModuleDir) {
                if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                    if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir)) { // if is a dir
                        $classNameForLoad = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
                        $filename =
                            $currentModulesParentDir . DIRECTORY_SEPARATOR . $singleModuleDir .
                            DIRECTORY_SEPARATOR . $classNameForLoad.'.php';
                        if (is_readable($filename)) {
                            require_once $filename;
                            return; } } } } }
    }

    public static function getInfoObjects() {
        $allInfoObjects = array();
        $allModuleParentDirectories = array("Extensions", "Modules", "Core");
        foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
            $currentModulesParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
            $modulesIndividualDirectories = scandir($currentModulesParentDir);
            foreach ($modulesIndividualDirectories as $singleModuleDir) {
                if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                    if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir)) { // if is a dir
                        $filesInModuleDirectory = scandir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir);
                        foreach ($filesInModuleDirectory as $fileInModuleDirectory) {
                            if (substr($fileInModuleDirectory, 0, 5) == "info.") {
                                require_once $currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir.DIRECTORY_SEPARATOR.$fileInModuleDirectory;
                                $className = '\Info\\'.$singleModuleDir.'Info' ;
                                $allInfoObjects[] = new $className(); } } } } } }
        return $allInfoObjects;
        }

        public static function getController($module) {
            $allModuleParentDirectories = array("Extensions", "Modules", "Core");
            foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
                $currentModulesParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
                $modulesIndividualDirectories = scandir($currentModulesParentDir);
                foreach ($modulesIndividualDirectories as $singleModuleDir) {
                    if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                        if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir)) { // if is a dir
                            if ($singleModuleDir == $module) {
                                $c = $currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir.DIRECTORY_SEPARATOR.
                                    'Controller'.DIRECTORY_SEPARATOR.$module.'.php' ;
                                require_once $c;
                                $className = '\Controller\\'.$singleModuleDir ;
                                return new $className(); } } } } }
            return null ;
        }

        public static function getAllControllers() {
            $controllers = array() ;
            $allModuleParentDirectories = array("Extensions", "Modules", "Core");
            foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
                $currentModulesParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
                $modulesIndividualDirectories = scandir($currentModulesParentDir);
                foreach ($modulesIndividualDirectories as $singleModuleDir) {
                    if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                        if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir)) { // if is a dir
                            if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir.DIRECTORY_SEPARATOR.'Controller')) { // if is a dir
                                $ctrlFiles = scandir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir.DIRECTORY_SEPARATOR.'Controller') ;
                                foreach ($ctrlFiles as $ctrlFile) {
                                    $c = $currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir.DIRECTORY_SEPARATOR.
                                        'Controller'.DIRECTORY_SEPARATOR.$ctrlFile ;
                                    if (!in_array($ctrlFile, array(".", ".."))) { // if not dot or double dot
                                        require_once $c; } }
                                $className = '\Controller\\'.$singleModuleDir ;
                                $controllers[] = new $className(); } } } } }
            return $controllers ;
        }

    public static function getSingleInfoObject($module) {
        $allModuleParentDirectories = array("Extensions", "Modules", "Core");
        foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
            $currentModulesParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
            $modulesIndividualDirectories = scandir($currentModulesParentDir);
            foreach ($modulesIndividualDirectories as $singleModuleDir) {
                if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                    if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir) &&
                        $singleModuleDir == $module ) { // if dirname is module were looking for
                        $filesInModuleDirectory = scandir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir);
                        foreach ($filesInModuleDirectory as $fileInModuleDirectory) {
                            if (substr($fileInModuleDirectory, 0, 5) == "info.") {
                                require_once $currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir.DIRECTORY_SEPARATOR.$fileInModuleDirectory;
                                $className = '\Info\\'.$singleModuleDir.'Info' ;
                                return new $className(); } } } } } }
    }

    public static function getAllModelsOfModule($module, $modelParams) {
        $allModuleParentDirectories = array("Extensions", "Modules", "Core");
        $modelsToReturn = array();
        foreach ($allModuleParentDirectories as $oneModuleParentDirectory) {
            $currentModulesParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oneModuleParentDirectory ;
            $modulesIndividualDirectories = scandir($currentModulesParentDir);
            foreach ($modulesIndividualDirectories as $singleModuleDir) {
                if (!in_array($singleModuleDir, array(".", ".."))) { // if not dot or double dot
                    if ( is_dir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir) && $singleModuleDir == $module ) { // if dirname is module were looking for
                        $filesInModelDirectory = scandir($currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir
                        .DIRECTORY_SEPARATOR.'Model');
                        foreach ($filesInModelDirectory as $fileInModelDirectory) {
                            if (!in_array($fileInModelDirectory, array(".", "..")) ) {
                                $fileToRequire = $currentModulesParentDir.DIRECTORY_SEPARATOR.$singleModuleDir .DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR.$fileInModelDirectory;
                                require_once $fileToRequire ;
                                $classNameToInclude = substr($fileInModelDirectory, 0, strlen($fileInModelDirectory)-4) ;
                                $className = '\Model\\'.$classNameToInclude;
                                $modelsToReturn[] = new $className($modelParams) ;
                            } } } } } }
        return $modelsToReturn ;
    }

}