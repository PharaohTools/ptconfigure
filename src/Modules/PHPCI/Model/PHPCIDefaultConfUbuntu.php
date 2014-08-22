<?php

Namespace Model;

//@todo finish off the template vars
class PHPConfUbuntu extends BaseTemplater {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "12.10") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPConf";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "setDefaultReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setOverrideReplacements", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplateFile", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "setTemplate", "params" => array()) ),
        );
        $this->uninstallCommands = array();
        $this->programDataFolder = "/opt/PHPConf"; // command and app dir name
        $this->programNameMachine = "phpconf"; // command and app dir name
        $this->programNameFriendly = "PHP Conf!"; // 12 chars
        $this->programNameInstaller = "PHP Conf";
        $this->targetLocation = "/etc/php5/apache2/php.ini" ;
        $this->initialize();
    }

    protected function setDefaultReplacements() {
        // set array with default values
        $this->replacements = array(
            "user_ini.filename" => ".user.ini",
            "user_ini.cache_ttl" => "300",
            "engine" => "On",
            "short_open_tag" => "On",
            "asp_tags" => "Off",
            "precision" => "14",
            "y2k_compliance" => "On",
            "output_buffering" => "4096",
            "output_handler" => "",
            "zlib.output_compression" => "Off",
            "zlib.output_compression_level" => "-1",
            "zlib.output_handler" => "",
            "implicit_flush" => "Off",
            "unserialize_callback_func" => "",
            "serialize_precision" => "17",
            "allow_call_time_pass_reference" => "Off",
            "safe_mode" => "Off",
            "safe_mode_gid" => "Off",
            "safe_mode_exec_dir" => "",
            "safe_mode_allowed_env_vars" => "PHP_",
            "safe_mode_protected_env_vars" => "LD_LIBRARY_PATH",
            "open_basedir" => "",
            "disable_functions" => "pcntl_alarm,pcntl_fork,pcntl_waitpid,pcntl_wait,pcntl_wifexited,pcntl_wifstopped,pcntl_wifsignaled,pcntl_wexitstatus,pcntl_wtermsig,pcntl_wstopsig,pcntl_signal,pcntl_signal_dispatch,pcntl_get_last_error,pcntl_strerror,pcntl_sigprocmask,pcntl_sigwaitinfo,pcntl_sigtimedwait,pcntl_exec,pcntl_getpriority,pcntl_setpriority,",
            "disable_classes" => "",
            "ignore_user_abort" => "On",
            "realpath_cache_size" => "16k",
            "realpath_cache_ttl" => "120",
            "zend.enable_gc" => "On",
            "expose_php" => "On",
            "max_execution_time" => "30",
            "max_input_time" => "60",
            "max_input_nesting_level" => "64",
            "max_input_vars" => "1000",
            "memory_limit" => "128M",
        ) ;
    }

    protected function setTemplateFile() {
        $this->templateFile = str_replace("Model", "Templates", dirname(__FILE__) ) ;
        $this->templateFile .= DIRECTORY_SEPARATOR."php.ini" ;
    }

}