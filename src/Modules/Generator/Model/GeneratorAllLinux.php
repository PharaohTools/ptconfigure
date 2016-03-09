<?php

Namespace Model;

class GeneratorAllLinux extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function askWhetherToGenerateModule() {
        return $this->performGenerateModule();
    }

    public function performGenerateModule() {
        if ($this->askForGeneratorExecute() != true) { return false; }
        $sourcePath = $this->getSourceFilePath() ;
        $targetPath = $this->getTargetFilePath() ;
        $this->doGenerateModule($sourcePath, $targetPath) ;
        return true;
    }

    private function doGenerateModule($source, $target) {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $original_module = $source ;
        $new_module = $target ;
        $parent_module_path = __DIR__.DS."src".DS."Modules".DS ;
        $original_module_path = $parent_module_path.$original_module ;
        $original_module_files = array_slice(scandir($original_module_path), 2);

        $logging->log("Going to copy from {$original_module} to {$new_module}", $this->getModuleName());
        $logging->log("Original Module Path is {$original_module_path}", $this->getModuleName());
        $logging->log("New Module Path is ".$parent_module_path.$new_module, $this->getModuleName());

        foreach ($original_module_files as $original_module_file) {

            $this_file = $original_module_path.DS.$original_module_file ;

            if (is_file($this_file)) {
                echo "something ok. copy the file.\n" ;
                doAFileMove($original_module_path.DS.$original_module_file, $original_module, $new_module) ;
            }

            else if (is_dir($this_file)) {
                echo "something ok. do the dir.\n" ;
                doADirMove($this_file, $original_module, $parent_module_path.$new_module, $new_module) ;
            }

            else {
                echo "something shit. apparently not a file or a dir.\n" ;
                exit(1) ;
            }

        }

        $comm = "cp -r $source $target" ;
        $logging->log("Executing $comm", $this->getModuleName());
        self::executeAndOutput($comm) ;
    }

    private function askForGeneratorExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Generator files?';
        return self::askYesOrNo($question);
    }

    private function getSourceFilePath(){
        if (isset($this->params["source"])) { return $this->params["source"] ; }
        else { $question = "Enter source file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    private function getTargetFilePath(){
        if (isset($this->params["target"])) { return $this->params["target"] ; }
        else { $question = "Enter target file path"; }
        $input = self::askForInput($question) ;
        return ($input=="") ? false : $input ;
    }

    protected function doAFileMove($original_module_file, $original_module, $new_mod) {
        $target_file = str_replace($original_module, $new_mod, $original_module_file) ;
        echo "Doing a file move from {$original_module_file} to {$target_file} \n" ;
        $file_data = file_get_contents($original_module_file) ;
        $file_data = str_replace($original_module, $new_mod, $file_data) ;
        global $translates ;
        foreach ($translates as $search => $replace) {
            $file_data = str_replace($search, $replace, $file_data) ;  }
        $res = file_put_contents($target_file, $file_data) ;
        return ($res !== false) ? true : false ;
    }

    protected function doADirMove($dir, $original_module, $target_dir, $new_module) {
        $end_dir = basename($dir) ;
        $target_dir .= DS.$end_dir ;
        echo "Doing a dir move from ".$dir." to ".$target_dir." \n" ;
        echo "Making $target_dir \n" ;
        mkdir($target_dir, 0777, true) ;
        chmod($target_dir, 0777) ;
        $original_module_files = array_slice(scandir($dir), 2);
        foreach ($original_module_files as $original_module_file) {
            if (is_dir($dir.DS.$original_module_file)) {
    //            var_dump("start: ", $dir.DS.$original_module_file, $target_dir) ;
                doADirMove($dir.DS.$original_module_file, $original_module, $target_dir, $new_module) ; }
            else {
                doAFileMove($dir.DS.$original_module_file, $original_module, $new_module) ; } }
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
        global $translates ;
        $new_file_name = $path.DS.strtr($base, $translates) ;
        return $new_file_name ;
    }

}