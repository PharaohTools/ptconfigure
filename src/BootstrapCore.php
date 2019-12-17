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

        ini_set('display_errors', 0) ;
        ini_set('display_startup_errors', 0) ;
        date_default_timezone_set('Europe/London');

        $this->start = time() ;
        $date_format = date('H:i:s, d/m/Y', $this->start) ;
        $friendly = substr(PHARAOH_APP, 2) ;
        $friendly = ucfirst($friendly) ;

        $is_cli = $this->isCLI($argv_or_boot_params_null) ;
        if ($is_cli === true) {
            echo "[Pharaoh {$friendly}] [Start] Execution begun at ".$date_format.PHP_EOL;
        }
        $routeObject = new \Core\Router();
        $route = $routeObject->run($argv_or_boot_params_null);
        $emptyPageVars = array("messages"=>array(), "route"=>$route);
        $this->executeControl($route["control"], $emptyPageVars);
        $this->exitGracefully($is_cli);

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

    private function exitGracefully($is_cli = false) {
        // @note this must be the last executed line as it sets exit code
        $cur = time() ;
        $finish = $cur - $this->start ;
        $date_format = date('H:i:s, d/m/Y', $cur) ;
        $friendly = substr(PHARAOH_APP, 2) ;
        $friendly = ucfirst($friendly) ;
        if ($is_cli === true) {
            echo "[Pharaoh {$friendly}] [Exit] Execution finished at {$date_format}, after ".$finish." seconds ".PHP_EOL; }
        if (self::$exitCode == null) {
            exit(0) ; }
        else if (!is_int(self::$exitCode)) {
            if ($is_cli === true) {
                echo "[Pharaoh {$friendly}] [Exit] Non Integer Exit Code Attempted".PHP_EOL; }
            exit(1) ; }
        else {
            if ($is_cli === true) {
                echo "[Pharaoh {$friendly}] [Exit] Exiting with exit code: ".self::$exitCode.PHP_EOL; }
            exit(self::$exitCode) ; }
    }

    private function isCLI($argv_or_boot_params_null) {
        foreach ($argv_or_boot_params_null as $param) {
            $output_prefix = substr($param, 0, 16) ;
            $output_format = substr($param, 16) ;
            if ($output_prefix === '--output-format=') {
                if ($output_format === 'cli') {
                    return true ;
                } else {
                    return false ;
                }
            }
        }
        return false ;
    }

}