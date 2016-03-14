<?php

Namespace Model;

class GeneratorAllLinux extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $translates ;

    public function askWhetherToGenerateFromModule() {
        return $this->performGenerateModule();
    }

    public function performGenerateModule() {
        $sourcePath = $this->getSourceFilePath() ;
        $targetPath = $this->getTargetFilePath() ;
        return $this->doGenerateModule($sourcePath, $targetPath) ;
    }

    protected function doGenerateModule($source, $target) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $original_module = $source ;
        $new_module = $target ;

        $found_pos = strpos($target, DS) ;
        if ($found_pos !== false) {
            $mod_dir = substr($target, 0, $found_pos) ;
            $new_module = substr($target, $found_pos+1) ; }
        else { $mod_dir = 'Modules' ; }

        $ptc_trim = rtrim(PTCCOMM, " ") ;
        $parent_module_path = PFILESDIR.$ptc_trim.DS.$ptc_trim.DS."src".DS.$mod_dir.DS ;
        $original_module_path = $parent_module_path.$original_module ;

        var_dump("scand: ", $original_module_path) ;
        $original_module_files = array_slice(scandir($original_module_path), 2) ;

        $logging->log("Going to copy from {$original_module} to {$new_module}", $this->getModuleName());
        $logging->log("Original Module Path is {$original_module_path}", $this->getModuleName());
        $logging->log("New Module Path is ".$parent_module_path.$new_module, $this->getModuleName());
        foreach ($original_module_files as $original_module_file) {
            $this_file = $original_module_path.DS.$original_module_file ;
            if (is_dir($this_file)) {
                $logging->log("Something ok. Do the dir.") ;
                $res = $this->doADirMove($this_file, $original_module, $parent_module_path.$new_module, $new_module) ; }
            else {
                $logging->log("Something ok. Copy the file") ;
                $res = $this->doAFileMove($original_module_path.DS.$original_module_file, $original_module, $new_module) ; }
            if ($res == false) {
                $logging->log(
                    "Something bad happened. Apparently not a file or a dir",
                    $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        return true ;
    }

    protected function askForGeneratorExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Generator files?';
        return self::askYesOrNo($question);
    }

    protected function getSourceFilePath(){
        if (isset($this->params["source"])) { return $this->params["source"] ; }
        else { $question = "Enter source file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function  getTargetFilePath(){
        if (isset($this->params["target"])) { return $this->params["target"] ; }
        else { $question = "Enter target file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function doAFileMove($original_module_file, $original_module, $new_mod) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $target_file = str_replace($original_module, $new_mod, $original_module_file) ;
        $logging->log("Doing a file move from {$original_module_file} to {$target_file}", $this->getModuleName()) ;
        $file_data = file_get_contents($original_module_file) ;
        $file_data = str_replace($original_module, $new_mod, $file_data) ;
        $this->translates ;
        foreach ($this->translates as $search => $replace) {
            $file_data = str_replace($search, $replace, $file_data) ;  }
        $res = file_put_contents($target_file, $file_data) ;
        return ($res !== false) ? true : false ;
    }

    protected function doADirMove($dir, $original_module, $target_dir, $new_module) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $end_dir = basename($dir) ;
        $target_dir .= DS.$end_dir ;
        $logging->log("Doing a dir move from ".$dir." to ".$target_dir, $this->getModuleName()) ;
        $logging->log("Making $target_dir", $this->getModuleName()) ;
        mkdir($target_dir, 0777, true) ;
        chmod($target_dir, 0777) ;
        $original_module_files = array_slice(scandir($dir), 2);
        foreach ($original_module_files as $original_module_file) {
            if (is_dir($dir.DS.$original_module_file)) {
                $res = $this->doADirMove($dir.DS.$original_module_file, $original_module, $target_dir, $new_module) ; }
            else {
                $res = $this->doAFileMove($dir.DS.$original_module_file, $original_module, $new_module) ; }
            if ($res == false) {
                $logging->log(
                    "Something bad happened. Apparently not a file or a dir",
                    $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        return true ;
    }

    protected function loopOurTranslates($translates) {
        $pairs = explode(",", $translates) ;
        $new_translates = array() ;
        foreach ($pairs as $pair) {
            $search = substr($pair, 0, strpos($pair, ":") ) ;
            $replace = substr($pair, strpos($pair, ":")+1 ) ;
            $new_translates[$search] = $replace ; }
        return $new_translates ;
    }

    protected function translateFilename($file_name) {
        $base = basename($file_name) ;
        $last_ds = strrpos ($file_name , DS ) ;
        $path = substr($file_name, 0, $last_ds) ;
        $new_file_name = $path.DS.strtr($base, $this->translates) ;
        return $new_file_name ;
    }

}