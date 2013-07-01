<?php

Namespace Core;

class AutoLoader{

    public function launch() {
      spl_autoload_register('Core\autoLoader::autoLoad');
    }

    public static function autoLoad($className) {
      // look in core
      $classNameForLoad = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
      $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $classNameForLoad.'.php';
      if (is_readable($filename)) {
        require_once $filename;
        return; }
      // look in extensions
      $extensionParentDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . "Extensions" ;
      $extensionIndividualDirectories = scandir($extensionParentDir);
      foreach ($extensionIndividualDirectories as $singleExtensionDir) {
        if (!in_array($singleExtensionDir, array(".", ".."))) { // if not dot or double dot
          if ( is_dir($extensionParentDir.DIRECTORY_SEPARATOR.$singleExtensionDir)) { // if is a dir
            $classNameForLoad = str_replace('\\' , DIRECTORY_SEPARATOR, $className);
            $filename =
              $extensionParentDir . DIRECTORY_SEPARATOR . $singleExtensionDir .
              DIRECTORY_SEPARATOR . $classNameForLoad.'.php';
            echo $filename;
            if (is_readable($filename)) {
              require_once $filename;
              return;
            }
          }
        }
      }
    }

}