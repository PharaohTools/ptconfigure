<?php

Namespace Core;

$bootStrap = new BootStrap();

$argv_or_null = (isset($argv)) ? $argv : null ;
$bootStrapParams = (isset($_ENV['cleo_bootstrap'])) ? unserialize($_ENV['cleo_bootstrap']) : $argv_or_null ;
$bootStrap->main($bootStrapParams);

class BootStrap {

    private static $exitCode ;

    public function __construct() {
        require_once("Constants.php");
        require_once("AutoLoad.php");
        $autoLoader = new autoLoader();
        $autoLoader->launch();
    }

    public static function setExitCode($exitCode){
        self::$exitCode = $exitCode ;
    }

    public function main($argv_or_boot_params_null) {
      $routeObject = new \Core\Router();
      $route = $routeObject->run($argv_or_boot_params_null);
      $emptyPageVars = array("messages"=>array(), "route"=>$route);
      $this->executeControl($route["control"], $emptyPageVars);
      $this->exitGracefully();
    }

    public function executeControl($controlToExecute, $pageVars=null) {
        $control = new \Core\Control();
        $controlResult = $control->executeControl($controlToExecute, $pageVars);
        try {
            if ($controlResult["type"]=="view") {
                $this->executeView( $controlResult["view"], $controlResult["pageVars"] ); }
            else if ($controlResult["type"]=="control") {
                $this->executeControl( $controlResult["control"], $controlResult["pageVars"] ); }
        } catch (\Exception $e) {
            throw new \Exception( 'No controller result type specified', 0, $e); }
    }

    private function executeView($viewTemplate, $viewVars) {
        $view = new \Core\View();
        $view->executeView($viewTemplate, $viewVars);
    }

    private function exitGracefully() {
        // @note this must be the last executed line as it sets exit code
        if (self::$exitCode == null) {
            exit(0) ; }
        else if (!is_int(self::$exitCode)) {
            echo "[Pharaoh Exit] Non Integer Exit Code Attempted\n" ;
            exit(1) ; }
        else {
            echo "[Pharaoh Exit] Exiting with exit code: ".self::$exitCode."\n" ;
            exit(self::$exitCode) ; }
    }

}