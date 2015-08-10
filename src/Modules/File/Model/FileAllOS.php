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
            "should-have-line" => "performShouldHaveLine",
            "should-not-have-line" => "performShouldNotHaveLine",
            "replace-line" => "performReplaceLine",
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
        return $this->exists();
    }

    public function performAppendLine() {
        $this->setFile();
        $this->setSearchLine();
        $this->read();
        $this->append();
        return true ;
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
        $this->read();
        $this->replaceLine();
        return $this->write();
    }

    public function setFile($fileName = null) {
        if (isset($fileName)) {
            $this->fileName = $fileName; }
        else if (isset($this->params["file"])) {
            $this->fileName = $this->params["file"]; }
        else {
            $this->fileName = self::askForInput("Enter File Path:", true); }
    }

    public function setSearchLine($searchLine = null) {
        if (isset($searchLine)) {
            $this->search = $searchLine; }
        else if (isset($this->params["search"])) {
            $this->search = $this->params["search"]; }
        else {
            $this->search = self::askForInput("Enter line of text to search for:", true); }
    }

    public function setReplaceLine($replaceLine = null) {
        if (isset($replaceLine)) {
            $this->replace = $replaceLine; }
        else if (isset($this->params["replace"])) {
            $this->replace = $this->params["replace"]; }
        else {
            $this->replace = self::askForInput("Enter line of text to replace/add:", true); }
    }

    public function read() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Reading File {$this->fileName}", $this->getModuleName()) ;
        $this->fileData = file_get_contents($this->fileName);
        return $this->fileData ;
    }

    public function exists() {
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
        $logging->log("Creating File {$this->fileName}", $this->getModuleName()) ;
        touch($this->fileName);
        return $this;
    }

    public function filedelete() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Deleting File {$this->fileName}", $this->getModuleName()) ;
        $system = new \Model\SystemDetection();
        $thisSystem = $system->getModel($this->params);
        if (!in_array($thisSystem->os, array("Windows", "WINNT") ) ) {
            $comm = 'del /S /Q ' ; }
        else {
            $comm = "rm -f " ; }
        $comm .= $this->fileName ;
        self::executeAndOutput($comm, $this->fileName." Deleted") ;
        return $this;
    }

    public function replaceIfPresent($needle, $newNeedle) {
        if ($this->contains($needle)) {
            if ($needle instanceof RegExp) {
                $newContent = preg_replace($needle->regexp, $newNeedle, $this->fileData); }
            else {
                $newContent = str_replace($needle, $newNeedle, $this->fileData); }
            $this->write($newContent); }
        return $this;
    }

    public function removeIfPresent($needle) {
        if ($this->contains($needle)) {
            if ($needle instanceof RegExp) {
                $newContent = preg_replace($needle->regexp, "", $this->fileData); }
            else {
                $newContent = str_replace($needle, "", $this->fileData); }
            $this->write($newContent); }
        return $this;
    }

    public function contains($needle) {
        if ($needle instanceof RegExp) {
            return preg_match($needle->regexp, $this->fileData); }
        else {
            return strstr($this->read(), $needle); }
    }

    public function append($str = null) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (is_null($str)) {$str = $this->params["replace"].PHP_EOL ; }
        if ($this->params["after-line"]) {
            $logging->log("Looking for line to append after...", $this->getModuleName()) ;
            $fileData = "" ;
            $fileLines = explode("\n", $this->fileData) ;
            foreach ($fileLines as $fileLine) {
                if ($fileLine == $this->params["after-line"]) {
                    $logging->log("Found line we're looking for, appending data after it", $this->getModuleName()) ;
                    $fileData .= "$fileLine\n" ;
                    $fileData .= "$str\n";}
                else { $fileData .= "$fileLine\n" ; } } }
        else { $fileData = $this->fileData . $str ; }
        return $this->write($fileData);
    }

    public function chmod($string) {
        chmod($this->fileName, $string);
    }

    public function findString($needle) {
        if ($needle instanceof RegExp) {
            preg_match_all($needle->regexp, $this->read(), $m);
            if (isset($m[1])) {
                return $m[1]; }
            if (isset($m[0])) {
                return $m[0]; } }
        else {
            if (strstr($this->read(), $needle)) {
                return $needle; } }
        return null;
    }

    public function shouldHaveLines($lines) {
        foreach($lines as $line) {
            $this->shouldHaveLine($line); }
    }

    public function replaceLine($oldline = null, $newline = null) {
        $string = ($oldline === null) ? $this->search : $oldline ;
        $newline = ($newline === null) ? $this->replace : $newline ;
        if (!($string instanceof RegExp)) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if (substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n"; }
        if ($this->findString($searchString)) {
            $this->replaceIfPresent($oldline, $newline); }
        return $this;
    }

    public function shouldHaveLine($line = null) {
        $string = ($line === null) ? $this->search : $line ;
        if (!($string instanceof RegExp)) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if (substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n"; }
        if (!$this->findString($searchString)) {
            $this->append($string . "\n"); }
        return $this;
    }

    public function shouldNotHaveLine($line = null) {
        $string = ($line === null) ? $this->search : $line ;
        if (!($string instanceof RegExp)) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if (substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n"; }
        if ($this->findString($searchString)) {
            $this->replaceLine($string . "\n", ""); }
        return $this;
    }

}