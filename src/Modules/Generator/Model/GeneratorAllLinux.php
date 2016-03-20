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
        $this->translates = $this->getTranslates() ;
        return $this->doGenerateModule($sourcePath, $targetPath) ;
    }

    protected function doGenerateModule($source, $target) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $original_module = basename($source) ;
        $new_module = $target ;

        $found_pos = strpos($target, DS) ;
        if ($found_pos !== false) {
            $mod_dir = substr($target, 0, $found_pos) ;
            $new_module = substr($target, $found_pos+1) ; }
        else { $mod_dir = 'Extensions' ; }

        $ptc_trim = rtrim(PTCCOMM, " ") ;
        $ptc_parent = PFILESDIR.$ptc_trim.DS.$ptc_trim.DS."src".DS.$mod_dir.DS ;

        if (file_exists($source)) {
            $parent_module_path = str_replace(basename($original_module), "", $original_module) ;
            $original_module_path = $source ; }
        else {
            $parent_module_path = $ptc_parent ;
            $original_module_path = $parent_module_path.$source ; }
        $original_module_files = scandir($original_module_path) ;
        if (!is_array($original_module_files) || count($original_module_files) < 1) {
            $logging->log("Unable to scan files in $original_module", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $original_module_files = array_slice($original_module_files, 2) ;
        $parent_target_module_path = $ptc_parent ;
        $logging->log("Going to copy from {$original_module} to {$new_module}", $this->getModuleName());
        $logging->log("Original Module Path is {$original_module_path}", $this->getModuleName());
        $logging->log("New Module Path is ".$parent_target_module_path.$new_module, $this->getModuleName());

        foreach ($original_module_files as $original_module_file) {
            $this_file = $original_module_path.DS.$original_module_file ;
            if (is_dir($this_file)) {
                $res = $this->doADirMove($this_file, $source, $parent_target_module_path.$new_module, $new_module) ; }
            else {
                $res = $this->doAFileMove($original_module_path.DS.$original_module_file, $source, $new_module, $parent_target_module_path.$new_module) ; }
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

    protected function  getTranslates(){
        if (isset($this->params["translates"])) {
            $this->translates = $this->params["translates"] ;
            return $this->params["translates"] ; }
        return array() ;
    }

    protected function doAFileMove($original_module_file, $original_module, $new_mod, $target_dir = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $target_file = str_replace(basename($original_module), $new_mod, $original_module_file) ;

//        var_dump("tf1", $target_file) ;

        if ($target_dir !== null) {
            $start = strpos($original_module_file, $original_module ) ;
            $path = substr($original_module_file ,$start) ;
            $fin = basename($path) ;
            $target_file = $target_dir.DS.$fin ;
//            var_dump("tf2", $target_dir, $path, $fin ) ;
            $target_file = str_replace(basename($original_module), $new_mod, $target_file) ;
//            var_dump("tf3", $original_module_file, $original_module, "target dir", $target_dir) ;
        }

        $logging->log("Doing a file copy from {$original_module_file} to {$target_file}", $this->getModuleName()) ;
        $file_data = file_get_contents($original_module_file) ;
        $file_data = $this->translateData(basename($original_module), $new_mod, $file_data);
        $res = file_put_contents($target_file, $file_data) ;
        return ($res !== false) ? true : false ;
    }

    protected function translateData($original, $new, $file_data) {
        $file_data = str_replace($original, $new, $file_data) ;
        if (count($this->translates)>0) {
            foreach ($this->translates as $search => $replace) {
                $file_data = str_replace($search, $replace, $file_data) ;  } }
        $file_data = $this->loopOurTranslates($file_data) ;
        return $file_data ;
    }

    protected function doADirMove($dir, $original_module, $target_dir, $new_module) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $end_dir = basename($dir) ;
        $target_dir .= DS.$end_dir ;
        $logging->log("Doing a dir copy from ".$dir." to ".$target_dir, $this->getModuleName()) ;
        $logging->log("Making $target_dir", $this->getModuleName()) ;
        mkdir($target_dir, 0777, true) ;
        chmod($target_dir, 0777) ;
        $original_module_files = array_slice(scandir($dir), 2);
        foreach ($original_module_files as $original_module_file) {
            if (is_dir($dir.DS.$original_module_file)) {
                $res = $this->doADirMove($dir.DS.$original_module_file, $original_module, $target_dir, $new_module) ; }
            else {
                $res = $this->doAFileMove($dir.DS.$original_module_file, $original_module, $new_module, $target_dir) ; }
            if ($res == false) {
                $logging->log( "Something failed. Apparently not a file or a dir", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        return true ;
    }

    protected function loopOurTranslates($file_data) {
        $pairs = explode(",", $this->translates) ;
        $new_translates = array() ;
        foreach ($pairs as $pair) {
            $search = substr($pair, 0, strpos($pair, ":") ) ;
            $replace = substr($pair, strpos($pair, ":") + 1 ) ;
//            $new_translates[$search] = $replace ;
            $file_data = str_replace($search, $replace, $file_data) ; }
        return $file_data ;
    }

    protected function translateFilename($file_name) {
        $base = basename($file_name) ;
        $last_ds = strrpos ($file_name , DS ) ;
        $path = substr($file_name, 0, $last_ds) ;
        $new_file_name = $path.DS.strtr($base, $this->translates) ;
        return $new_file_name ;
    }

}