<?php

Namespace Model;

class FileAllOS extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $fileName ;
    protected $fileData ;
    protected $search ;
    protected $replace ;
    protected $actionsToMethods =
        array(
            "exists" => "performFileExistenceCheck",
            "append" => "performAppendLine",
            "create" => "performCreation",
            "delete" => "performDeletion",
            "should-exist" => "performCreation",
            "should-have-line" => "performShouldHaveLine",
            "should-not-have-line" => "performShouldNotHaveLine",
            "replace-line" => "performReplaceLine",
            "replace-text" => "performReplaceText",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->programDataFolder = "";
        $this->programNameMachine = "file"; // command and app dir name
        $this->programNameFriendly = "!File!!"; // 12 chars
        $this->programNameInstaller = "File";
        $this->initialize();
    }

    public function performFileExistenceCheck() {
        $this->setFile();
        return $this->exists();
    }

    public function performDeletion() {
        $this->setFile();
        $this->filedelete();
        return ($this->exists()==false) ? true : false ;
    }

    public function performCreation() {
        $this->setFile();
//        $this->create();
        return $this->shouldExist();
    }

    public function performAppendLine() {
        $this->setFile();
        $this->read();
        $this->append();
        return $this->write() ;
    }

    protected function askInput(){
        if (isset($input)) {
            $this->input = $input; }
        else if (isset($this->params["file"])) {
            $this->input = $this->params["file"]; }
        else {
            $this->input = self::askForInput("Enter the input for append:", true); }
    }

    public function performShouldHaveLine() {
        $this->setFile();
        $this->setSearchLine();
        $this->read();
        $this->shouldHaveLine();
        return $this->write();
    }

    public function performShouldNotHaveLine() {
        $this->setFile();
        $this->setSearchLine();
        $this->read();
        $this->shouldNotHaveLine();
        return $this->write();
    }

    public function performReplaceLine() {
        $this->setFile();
        $this->setSearchLine();
        $this->setReplaceLine();
        $this->read();
        $this->replaceLine();
        return $this->write();
    }

    public function performReplaceText() {
        $this->setFile();
        $this->setSearchLine();
        $this->setReplaceLine();
        $this->read();
        $this->replaceText();
        return $this->write();
    }

    public function setFile($fileName = null) {
        if (isset($fileName)) {
            $this->fileName = $fileName; }
        else if (isset($this->params["file"])) {
            $this->fileName = $this->params["file"]; }
        else {
            $this->fileName = self::askForInput("Enter File Path:", true); }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Using File {$this->fileName}", $this->getModuleName()) ;
    }

    public function setSearchLine($searchLine = null) {
        if (isset($searchLine)) {
            $this->search = $searchLine; }
        else if (isset($this->params["search"])) {
            $this->search = $this->params["search"]; }
        else {
            $this->search = self::askForInput("Enter line of text to search for:", true); }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Searching For {$this->search}", $this->getModuleName()) ;
    }

    public function setReplaceLine($replaceLine = null) {
        if (isset($replaceLine)) {
            $this->replace = $replaceLine; }
        else if (isset($this->params["replace"])) {
            $this->replace = $this->params["replace"]; }
        else {
            $this->replace = self::askForInput("Enter line of text to replace/add:", true); }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Replacing with {$this->replace}", $this->getModuleName()) ;
    }

    public function read() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Reading File {$this->fileName}", $this->getModuleName()) ;
        $this->fileData = file_get_contents($this->fileName);
        var_dump('file read', $this->fileData) ;
        return $this->fileData ;
    }

    public function exists() {
        if(file_exists($this->fileName)){
            echo "File {$this->fileName} exists \n";
        }
        else{
            echo "File {$this->fileName} not exists \n";
        }
        return file_exists($this->fileName);
    }

    public function write($content = null) {
        if ($content == null) { $content = $this->fileData ; }
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Writing File {$this->fileName}", $this->getModuleName()) ;
        file_put_contents($this->fileName, $content);
        return $this;
    }

    public function create() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting to create File {$this->fileName}", $this->getModuleName()) ;
        $res = touch($this->fileName);
        if ($res === false) {
            $logging->log("Unable to write to File {$this->fileName}", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        else {
            $logging->log("Successfully written {$res} bytes to File {$this->fileName}", $this->getModuleName()) ;
            return $this ; }
    }

    public function filedelete() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Deleting File {$this->fileName}", $this->getModuleName()) ;
        if (in_array(PHP_OS, array("Windows", "WINNT") ) ) {
            $comm = 'del /S /Q ' ; }
        else {
            $comm = "rm -f " ; }
        $comm .= $this->fileName ;
        self::executeAndOutput($comm, $this->fileName." Deleted") ;
        return $this;
    }

    public function replaceIfPresent($needle, $newNeedle) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($this->contains($needle)) {
            $logging->log("$needle found in haystack, replacing", $this->getModuleName()) ;
            if ($needle instanceof RegExp) {
                $this->fileData = preg_replace($needle->regexp, $newNeedle, $this->fileData, -1, $count);
                $logging->log("$count replacements performed", $this->getModuleName()) ; }
            else {
                $this->fileData = str_replace($needle, $newNeedle, $this->fileData, $count);
                $logging->log("$count replacements performed", $this->getModuleName()) ; } }
        else {
            $logging->log("$needle not found in haystack", $this->getModuleName()) ; }
        return $this;
    }

    public function removeIfPresent($needle) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Removing line where present in file {$this->fileName}", $this->getModuleName()) ;
        if ($this->contains($needle)) {
            $logging->log("Specified line found, removing", $this->getModuleName()) ;
            if ($needle instanceof RegExp) {
                $this->fileData = preg_replace($needle->regexp, "", $this->fileData, -1, $count);
                $logging->log("$count removals performed", $this->getModuleName()) ; }
            else {
                $this->fileData = str_replace($needle, "", $this->fileData, $count);
                $logging->log("$count removals performed", $this->getModuleName()) ; } }
        else {
            $logging->log("Line not present, nothing to remove", $this->getModuleName()) ; }
        return $this;
    }

    public function contains($needle) {
        if ($needle instanceof RegExp) {
            return preg_match($needle->regexp, $this->fileData); }
        else {
            $st = strpos($this->fileData, $needle) !== false ; //;
            var_dump("stt",$st, "nd", $needle) ;
            return $st ; }
    }

    public function append($str = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting to append to file {$this->fileName}", $this->getModuleName()) ;
        if (is_null($str)) {$str = $this->params["line"].PHP_EOL ; }
        if (isset($this->params["after-line"]) && strlen($this->params["after-line"])>0) {
            $logging->log("Looking for line to append after...", $this->getModuleName()) ;
            $fileData = "" ;
            $fileLines = explode("\n", $this->fileData) ;
            foreach ($fileLines as $fileLine) {
                if ($fileLine == $this->params["after-line"]) {
                    $logging->log("Found line we're looking for, appending data after it", $this->getModuleName()) ;
                    $fileData .= "$fileLine\n" ;
                    $fileData .= "$str\n";}
                else { $fileData .= "$fileLine\n" ; } }
            $this->fileData = $fileData ; }
        else { $this->fileData .= $str ; }
        return true;
    }

    public function chmod($string) {
        chmod($this->fileName, $string);
    }

    public function findString($needle) {
        if ($needle instanceof RegExp) {
            preg_match_all($needle->regexp, $this->fileData, $m);
            if (isset($m[1])) {
//                var_dump("m1 ".$m[1]) ;
                return $m[1]; }
            if (isset($m[0])) {
//                var_dump("m2]0 ".$m[0]) ;
                return $m[0]; } }
        else {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            if (strpos($this->fileData, $needle)!==false) {
                $logging->log("Found Searched String", $this->getModuleName()) ;
                return true; }
            else {
                $logging->log("Unable to find Searched String", $this->getModuleName()) ;
                return false; } }
    }

    public function shouldHaveLines($lines) {
        foreach($lines as $line) {
            $this->shouldHaveLine($line); }
    }

    public function replaceLine($oldline = null, $newline = null) {
        $string = ($oldline === null) ? $this->search : $oldline ;
        $newline = ($newline === null) ? $this->replace : $newline ;
        if ($string instanceof RegExp) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if (substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n"; }
        if ($this->findString($searchString)) {
            $this->replaceIfPresent($searchString, $newline); }
        return $this;
    }

    public function replaceText($oldline = null, $newline = null) {
        $string = ($oldline === null) ? $this->search : $oldline ;
        $newline = ($newline === null) ? $this->replace : $newline ;
        $searchString = $string;
        if ($this->findString($searchString) !== false) {
            $this->replaceIfPresent($searchString, $newline); }
        return $this;
    }

    public function shouldHaveLine($line = null) {
        $string = ($line === null) ? $this->search : $line ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring line does exist in file {$this->fileName}", $this->getModuleName()) ;
        if ($string instanceof RegExp) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if (substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n"; }
        if ($this->findString($searchString) === false) {
            $this->append($searchString); }
        return $this;
    }

    public function shouldExist() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring existence of file {$this->fileName}", $this->getModuleName()) ;
        if ($this->exists()) {
            $logging->log("File exists as expected", $this->getModuleName()) ;
            return true ; }
        else {
            $logging->log("File does not exist, attempting to create", $this->getModuleName()) ;
            return $this->create() ; }
    }

    public function shouldNotHaveLine($line = null) {
        $string = ($line === null) ? $this->search : $line ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Ensuring line does not exist in file {$this->fileName}", $this->getModuleName()) ;
        if ($string instanceof RegExp) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if ($this->findString($searchString)) {
            $this->removeIfPresent($string); }
        return $this;
    }

}