<?php

Namespace Model;

class RoleExecutionAnyOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function performRoleExecution() {
        if ($this->askForRoleExecutionExecute() != true) {
            return false ;
        }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Roles", $this->getModuleName());
        $roles_dir = $this->getRolesDirectory() ;
        $role_path = $roles_dir.'roles.yml' ;
        $roles = $this->yamlParser($role_path) ;
        $roles = $roles[0] ;
        return $this->executeAllRoles($roles) ;
    }

    protected function displaySteps($steps) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Displaying Steps Object", $this->getModuleName());
        foreach ($steps as $step) {
            if ($step['type'] == 'role') {
                echo "{$step['type']}, {$step['name']}\n" ;
            } else if ($step['type'] == 'auto') {
                echo "{$step['type']}, {$step['path']}\n" ;
            }
        }
    }
    
    protected function getRoleObject($role) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Role Object", $this->getModuleName());
        $roles_dir = $this->getRolesDirectory() ;
        $config_path = $roles_dir.$role.DS.'config.yml' ;
        $role_directory = $roles_dir.$role.DS ;
        $role_index_path = $roles_dir.$role.DS.'index.dsl.yml' ;
        if (file_exists($config_path)) {
            $config = $this->yamlParser($config_path) ;
            if (isset($config->index)) {
                $role_index_path = $roles_dir.$role.DS.$config->index ;
            }
        }
        $logging->log("Using Role Index of $role_index_path", $this->getModuleName());
        $object = new \StdClass() ;
        $object->role_path = $role_index_path ;
        $object->role_directory = $role_directory ;
        if (isset($config['vars'])) {
            $object->vars = $config['vars'] ;
        }
//        var_dump($config, $object) ;
//        die() ;
        return $object ;
    }

    protected function getStepsObject($role) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Steps Object", $this->getModuleName());
        $roles_dir = $this->getRolesDirectory() ;
        $config_path = $roles_dir.$role.DS.'config.yml' ;
        $config = $this->yamlParser($config_path) ;
        $role_index_path = $roles_dir.$role.DS.'index.dsl.yml' ;
        if (file_exists($config_path)) {
            $config = $this->yamlParser($config_path) ;
            if (isset($config->index)) {
                $role_index_path = $roles_dir.$role.DS.$config->index ;
            }
        }
        $logging->log("Using Role Index of $role_index_path", $this->getModuleName());
        $object = new \StdClass() ;
        $object->role_path = $role_index_path ;
        if (isset($config->vars)) {
            $object->vars = $config->vars ;
        }
        return $object ;
    }

    protected function getAutoObject($auto) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Autopilot Object", $this->getModuleName());
        $logging->log("Using Autopilot Path of {$auto['path']}", $this->getModuleName());
        $object = new \StdClass() ;
        $object->auto = $auto['path'] ;
        if (isset($auto['vars'])) {
            $object->vars = $auto['vars'] ;
        }
        return $object ;
    }

    public function performStepsExecution() {
        if ($this->askForStepsExecutionExecute() != true) {
            return false ;
        }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading Steps", $this->getModuleName());
        $steps_path = $this->getStepsFile() ;
        if (!file_exists($steps_path)) {
            $logging->log("Unable to locate Steps file {$steps_path}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE);
            return false ;
        }
//        var_dump('$steps_path', $steps_path) ;
        $steps = $this->yamlParser($steps_path) ;
        $steps = $steps[0] ;
        $this->displaySteps($steps) ;
        return $this->executeAllSteps($steps) ;
    }

    protected function executeAllRoles($roles) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Role Execution", $this->getModuleName());

        foreach ($roles as $role) {

            $role_object = $this->getRoleObject($role) ;
            $roles_dir = $this->getRolesDirectory() ;
            $full_role_dir = $roles_dir.$role ;

            if (!is_dir($full_role_dir)) {
                $logging->log("Unable to find role directory {$full_role_dir}", $this->getModuleName()) ;
                return false ;
            }

            $comm  = 'cd  '.getcwd().DS. ' && ' ;
            $comm .= 'ptconfigure auto x ' ;
            $comm .= '--af="'.$role_object->role_path.'" ' ;
            $comm .= ' --vars="'.implode(',', $role_object->vars).'" ;' ;

            $logging->log("Executing $comm", $this->getModuleName()) ;
//            $res = 0 ;
            $res = $this->liveOutput($comm) ;
            if ($res == 0) {
                $logging->log("Role Execution Successful, Role: {$role}", $this->getModuleName()) ;
                echo "\n" ;
            } else {
                $logging->log("Role Execution Failed, Role: {$role}", $this->getModuleName()) ;
                return false ;
            }
        }
        return true ;
    }

    protected function executeAllSteps($steps) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting Steps Execution", $this->getModuleName());

        $summary = [] ;
        foreach ($steps as $step) {

            if ($step['type'] === 'auto') {

                if (substr($step['type'], 0, 1) == DS) {
                    $full_auto_path = $step['path'] ;
                } else if (substr($step['type'], 0, 1) !== DS) {
                    $full_auto_path = getcwd().DS.$step['path'] ;
                } else {
                    $logging->log("Unable to create autopilot path from {$step['path']}", $this->getModuleName()) ;
                    return false ;
                }

                if (!is_file($full_auto_path)) {
                    $logging->log("Unable to find autopilot at {$full_auto_path}", $this->getModuleName()) ;
                    return false ;
                }

                $auto_object = $this->getAutoObject($step) ;

                $comm  = 'cd  '.getcwd().DS. ' && ' ;
                $comm .= PTCCOMM.' auto x ' ;
                if (isset($this->params['step-times'])) {
                    $comm .= '--step-times="true" ' ;
                }
                if (isset($this->params['step-numbers'])) {
                    $comm .= '--step-numbers="true" ' ;
                }
                $comm .= '--af="'.$full_auto_path.'" ' ;
                if (isset($this->params['vars'])) {
                    if (is_string($this->params['vars'])) {
                        $stringy1 = $this->params['vars'] ;
                    } elseif (is_array($this->params['vars'])) {
                        $stringy1 = implode(',', $this->params['vars']) ;
                    }
                }
                if (isset($auto_object->vars)) {
                    if (is_string($auto_object->vars)) {
                        $stringy2 = $auto_object->vars ;
                    } elseif (is_array($auto_object->vars)) {
                        $stringy2 = implode(',', $auto_object->vars) ;
                    }
                    if (isset($stringy1)) {
                        $comm .= ' --vars="'.$stringy1.','.$stringy2.'" ;' ;
                    } else {
                        $comm .= ' --vars="'.$stringy2.'" ;' ;
                    }
                } else if (isset($stringy1)) {
                    $comm .= ' --vars="'.$stringy1.'" ;' ;
                }

                $logging->log("Executing $comm", $this->getModuleName()) ;
                $res = $this->liveOutput($comm) ;
                if ($res == 0) {
                    $logging->log("Autopilot Execution Successful", $this->getModuleName()) ;
                } else {
                    $logging->log("Autopilot Execution Failed", $this->getModuleName()) ;
                    return false ;
                }
            } else if ($step['type'] === 'role') {

                $role_object = $this->getRoleObject($step['name']) ;
                $roles_dir = $this->getRolesDirectory() ;
                $full_role_dir = $roles_dir.$step['name'] ;
                if (!is_dir($full_role_dir)) {
                    $logging->log("Unable to find role directory {$full_role_dir}", $this->getModuleName()) ;
                    return false ;
                }
                $comm  = 'cd  '.getcwd().DS. ' && ' ;
                $comm .= 'ptconfigure auto x ' ;
                if (isset($this->params['step-times'])) {
                    $comm .= '--step-times="true" ' ;
                }
                if (isset($this->params['step-numbers'])) {
                    $comm .= '--step-numbers="true" ' ;
                }
                $comm .= '--af="'.$role_object->role_path.'" ' ;
                if (isset($this->params['vars'])) {
                    if (is_string($this->params['vars'])) {
                        $stringy1 = $this->params['vars'] ;
                    } elseif (is_array($this->params['vars'])) {
                        $stringy1 = implode(',', $this->params['vars']) ;
                    }
                }
                if (isset($role_object->vars)) {
                    $stringy2 = $role_object->vars ;
                    if (is_string($role_object->vars)) {
                        $stringy2 = $role_object->role_directory.$role_object->vars ;
                    } elseif (is_array($role_object->vars)) {
                        $all_var_files = [] ;
                        foreach ($role_object->vars as $one_var_file) {
                            $all_var_files[] = $role_object->role_directory.$one_var_file ;
                        }
                        $stringy2 = implode(',', $all_var_files) ;
                    }
                    if (isset($stringy1)) {
                        $comm .= ' --vars="'.$stringy2.','.$stringy1.'" ;' ;
                    } else {
                        $comm .= ' --vars="'.$stringy2.'" ;' ;
                    }
                } else if (isset($stringy1)) {
                    $comm .= ' --vars="'.$stringy1.'" ;' ;
                }

                $logging->log("Executing $comm", $this->getModuleName()) ;
                $res = $this->liveOutput($comm) ;
                if ($res == 0) {
                    $logging->log("Role Execution Successful, Role: {$step['name']}", $this->getModuleName()) ;
                    echo "\n" ;
                    $step['result'] = 'Success' ;
                    $summary[] = $step ;
                } else {
                    $logging->log("Role Execution Failed, Role: {$step['name']}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                    echo "\n" ;
                    $step['result'] = 'Fail' ;
                    $summary[] = $step ;
                    $this->displayStepSummary($summary) ;
                    return false ;
                }
            }
        }
        $this->displayStepSummary($summary) ;
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
//            if ($process::OUT === $type) {
                echo $data;
//            } else { // $process::ERR === $type
//                echo "ERR: ".$data;
//            }
        }
        return $process->getExitCode();
    }

    protected function askForRoleExecutionExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Execute Roles?';
        return self::askYesOrNo($question);
    }

    protected function askForStepsExecutionExecute() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Execute Steps?';
        return self::askYesOrNo($question);
    }

    protected function getRolesDirectory() {
        if (isset($this->params["role-dir"]) &&
            strlen($this->params["role-dir"])>0) {
                if (substr($this->params["role-dir"], -1, 1) !== DS) {
                    $this->params["role-dir"] .= DS ;
                }
                if (is_dir(getcwd().DS.$this->params["role-dir"])) {
                    return getcwd().DS.$this->params["role-dir"] ;
                }
                return $this->params["role-dir"] ;
        }
        return getcwd().DS.'roles'.DS ;
    }

    protected function getRoleFile() {
        if (isset($this->params["rolefile"])) { return $this->params["rolefile"] ; }
        return getcwd().DS.'roles.yml' ;
    }

    protected function getStepsFile() {
        if (isset($this->params["steps"])) { return $this->params["steps"] ; }
        if (isset($this->params["stepsfile"])) { return $this->params["stepsfile"] ; }
        return getcwd().DS.'steps.yml' ;
    }

    protected function displayStepSummary($steps) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Displaying Steps Summary", $this->getModuleName());
        foreach ($steps as $step) {
            if ($step['type'] == 'role') {
                echo "{$step['result']}, {$step['type']}, {$step['name']}\n" ;
            } else if ($step['type'] == 'auto') {
                echo "{$step['result']}, {$step['type']}, {$step['path']}\n" ;
            }
        }
    }

}