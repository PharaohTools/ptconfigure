<?php

Namespace Core;

class BootStrap {

    private static $exitCode ;
    private $start ;

    public function __construct() {
        require_once(__DIR__.DIRECTORY_SEPARATOR."Constants.php");
        require_once(__DIR__.DIRECTORY_SEPARATOR."AutoLoad.php");
        $autoLoader = new autoLoader();
        $autoLoader->launch();
    }

    public static function setExitCode($exitCode){
        self::$exitCode = $exitCode ;
    }

    public static function getExitCode(){
        return (is_null(self::$exitCode)) ? 0 : self::$exitCode ;
    }

    public function main($argv_or_boot_params_null) {
        date_default_timezone_set('Europe/London');
        $this->start = time() ;
        $date_format = date('H:i:s, d/m/Y', $this->start) ;
        $friendly = substr(PHARAOH_APP, 2) ;
        $friendly = ucfirst($friendly) ;
        echo "[Pharaoh {$friendly}] [Start] Execution begun at ".$date_format.PHP_EOL;
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
            if ($controlResult["type"] === "view") {
                $this->executeView( $controlResult["view"], $controlResult["pageVars"] ); }
            else if ($controlResult["type"] === "control") {
                $this->executeControl( $controlResult["control"], $controlResult["pageVars"] ); }
        } catch (\Exception $e) {
            throw new \Exception( 'No controller result type specified', 0, $e);
        }
    }

    private function executeView($viewTemplate, $viewVars) {
        $view = new \Core\View();
        $view->executeView($viewTemplate, $viewVars);
    }

    private function exitGracefully() {
        // @note this must be the last executed line as it sets exit code
        $cur = time() ;
        $finish = $cur - $this->start ;
        $date_format = date('H:i:s, d/m/Y', $cur) ;
        $friendly = substr(PHARAOH_APP, 2) ;
        $friendly = ucfirst($friendly) ;
        echo "[Pharaoh {$friendly}] [Exit] Execution finished at {$date_format}, after ".$finish." seconds ".PHP_EOL;
        if (self::$exitCode == null) {
            exit(0) ; }
        else if (!is_int(self::$exitCode)) {
            echo "[Pharaoh {$friendly}] [Exit] Non Integer Exit Code Attempted".PHP_EOL; ;
            exit(1) ; }
        else {
            echo "[Pharaoh {$friendly}] [Exit] Exiting with exit code: ".self::$exitCode.PHP_EOL; ;
            exit(self::$exitCode) ; }
    }

}