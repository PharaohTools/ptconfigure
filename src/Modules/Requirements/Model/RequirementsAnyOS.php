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
        $requirements = $this->getRequirementsObject();
//
        return $this->executeAllRequirements($requirements) ;
    }

    protected function getRequirementsObject() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
//        $logging->log("Loading Requirements Object", $this->getModuleName());
        $logging->log("Loading Requirements", $this->getModuleName());

        $requirements_dir = $this->getRequirementsDirectory() ;
        $requirements_path = $requirements_dir.'requirements.yml' ;
        $logging->log("Using Requirements Path of $requirements_path", $this->getModuleName());
        $requirements = $this->yamlParser($requirements_path) ;
        return $requirements ;
    }


    protected function executeAllRequirements($requirements) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Requirements Install", $this->getModuleName());

        foreach ($requirements as $requirement) {


            var_dump($requirement) ;

//            if (!is_dir($full_role_dir)) {
//                $logging->log("Unable to find role directory {$full_role_dir}", $this->getModuleName()) ;
//                return false ;
//            }

            $comm  = 'cd  '.getcwd().DS. ' && ' ;
            $comm .= 'ptconfigure auto x ' ;
            $comm .= '--af="'.$requirement_object->role_path.'" ' ;
            $comm .= ' --vars="'.implode(',', $requirement_object->vars).'" ;' ;

            $logging->log("Executing $comm", $this->getModuleName()) ;
//            $res = 0 ;
            $res = $this->liveOutput($comm) ;
            if ($res == 0) {
                $logging->log("Requirement Execution Successful", $this->getModuleName()) ;
            } else {
                $logging->log("Requirement Execution Failed", $this->getModuleName()) ;
                return false ;
            }
        }
        return true ;
    }

    public function yamlParser($file_path) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (function_exists('yaml_parse_file')) {
            $logging->log("Using PHP Native Yaml Parser", $this->getModuleName()) ;
            $unformatted = yaml_parse_file($file_path) ;
        } else {
            $logging->log("Using Yaml Parser Library", $this->getModuleName()) ;
            require dirname(__DIR__).DS.'Libraries'.DS.'Spyc.php' ;
            $unformatted = \Spyc::YAMLLoad($file_path) ;
        }
        return $unformatted ;
    }

    protected function liveOutput($comm) {
        require_once(dirname(__DIR__).DS.'Libraries'.DS.'vendor'.DS.'autoload.php') ;
        $process = new \Symfony\Component\Process\Process($comm);
        $process->setTimeout(0);
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo $data;
            } else { // $process::ERR === $type
                echo "ERR: ".$data;
            }
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

    protected function getRequirementsDirectory() {
        if (isset($this->params["requirements-dir"]) &&
            strlen($this->params["requirements-dir"])>0) {
                if (substr($this->params["requirements-dir"], -1, 1) !== DS) {
                    $this->params["requirements-dir"] .= DS ;
                }
                if (is_dir(getcwd().DS.$this->params["requirements-dir"])) {
                    return getcwd().DS.$this->params["requirements-dir"] ;
                }
                return $this->params["requirements-dir"] ;
        }
        return getcwd().DS.'requirements'.DS ;
    }

    protected function getRequirementFile() {
        if (isset($this->params["requirements-file"])) { return $this->params["requirements-file"] ; }
        return getcwd().DS.'requirements.yml' ;
    }

}