<?php

Namespace Model;

class RequirementsAnyOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function performRequirements() {
        if ($this->askForRequirementsExecute() != true) {
            return false ;
        }

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $requirements = $this->getRequirementsObject();
        if ($requirements === false) {
            $logging->log("Unable to load Requirements", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false ;
        }
//
        return $this->executeAllRequirements($requirements) ;
    }

    protected function getRequirementsObject() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Requirements", $this->getModuleName());
        $requirements_path = $this->getRequirementsFile() ;
        $logging->log("Using Requirements Path of $requirements_path", $this->getModuleName());
        if (!file_exists($requirements_path)) {
            $logging->log("Unable to find file $requirements_path", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false ;
        }
        $requirements = $this->yamlParser($requirements_path) ;
        $requirements = $requirements[0] ;
        return $requirements ;
    }

    protected function executeAllRequirements($requirements) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Requirements Install", $this->getModuleName());
        $role_dir = $this->getRolesDirectory() ;
        foreach ($requirements as $requirement) {
            $keys = array_keys($requirement) ;
            $new_role_directory = $keys[0] ;
            $git_source_url = $requirement[$new_role_directory] ;
            $full_role_dir = $role_dir.$new_role_directory ;
            if (is_dir($full_role_dir)) {
                $logging->log("Role exists at path {$full_role_dir}, skipping ", $this->getModuleName()) ;
                continue ;
            } else {
                $logging->log("Installing Role {$new_role_directory} to path {$full_role_dir} ", $this->getModuleName()) ;
                $comm  = 'git clone ' ;
                $comm .= $git_source_url.' ' ;
                $comm .= $full_role_dir ;
                $logging->log("Executing $comm", $this->getModuleName()) ;
                $res = $this->liveOutput($comm) ;
                if ($res == 0) {
                    $logging->log("Requirement Execution Successful", $this->getModuleName()) ;
                } else {
                    $logging->log("Requirement Execution Failed", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                    return false ;
                }
            }
        }
        return true ;
    }

    public function yamlParser($file_path) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
//        if (function_exists('yaml_parse_file')) {
//            $logging->log("Using PHP Native Yaml Parser", $this->getModuleName()) ;
//            $unformatted = yaml_parse_file($file_path) ;
//        } else {
            $logging->log("Using Yaml Parser Library", $this->getModuleName()) ;
            require dirname(__DIR__).DS.'Libraries'.DS.'Spyc.php' ;
            $unformatted = \Spyc::YAMLLoad($file_path) ;
//        }
        return $unformatted ;
    }

    protected function liveOutput($comm) {
        require_once(dirname(__DIR__).DS.'Libraries'.DS.'vendor'.DS.'autoload.php') ;
        $process = new \Symfony\Component\Process\Process($comm);
        $process->setTimeout(0);
        $process->start();
        foreach ($process as $type => $data) {
            echo $data;
//            if ($process::OUT === $type) {
//            } else { // $process::ERR === $type
//                echo "ERR: ".$data;
//            }
        }
        return $process->getExitCode();
    }

    protected function askForRequirementsExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Execute Requirements?';
        return self::askYesOrNo($question);
    }

    protected function getRolesDirectory() {
        if (isset($this->params["roles-dir"]) &&
            strlen($this->params["roles-dir"])>0) {
                if (substr($this->params["roles-dir"], -1, 1) !== DS) {
                    $this->params["roles-dir"] .= DS ;
                }
                if (is_dir(getcwd().DS.$this->params["roles-dir"])) {
                    return getcwd().DS.$this->params["roles-dir"] ;
                }
                return $this->params["roles-dir"] ;
        }
        return getcwd().DS.'roles'.DS ;
    }

    protected function getRequirementsFile() {
        if (isset($this->params["requirements-file"])) { return $this->params["requirements-file"] ; }
        return getcwd().DS.'requirements.yml' ;
    }

}