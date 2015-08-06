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

    protected function performFileExistenceCheck() {
        $this->setFile();
        return $this->exists();
    }

    protected function performDeletion() {
        $this->setFile();
        $this->filedelete();
        return ($this->exists()==false) ? true : false ;
    }

    protected function performCreation() {
        $this->setFile();

        return $this->exists();
    }

    protected function performAppendLine() {
        $this->setFile();
        $this->setSearchLine();
        $this->append();
        return true ;
    }

    protected function performShouldHaveLine() {
        $this->setFile();
        $this->setSearchLine();
        return $this->shouldHaveLine();
    }

    protected function performShouldNotHaveLine() {
        $this->setFile();
        $this->setSearchLine();
        return $this->shouldNotHaveLine();
    }

    protected function performReplaceLine() {
        $this->setFile();
        $this->setSearchLine();
        $this->replaceLine();
        return $this->shouldNotHaveLine();
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
            $this->fileName = $searchLine; }
        else if (isset($this->params["search"])) {
            $this->fileName = $this->params["search"]; }
        else {
            $this->fileName = self::askForInput("Enter line of text to search for:", true); }
    }

    public function read() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Reading File {$this->fileName}", $this->getModuleName()) ;
        return file_get_contents($this->fileName);
    }

    public function exists() {
        return file_exists($this->fileName);
    }

    public function write($content) {
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
            $content = $this->read();
            if ($needle instanceof RegExp) {
                $newContent = preg_replace($needle->regexp, $newNeedle, $content); }
            else {
                $newContent = str_replace($needle, $newNeedle, $content); }
            $this->write($newContent); }
        return $this;
    }

    public function removeIfPresent($needle) {
        if ($this->contains($needle)) {
            $content = $this->read();
            if ($needle instanceof RegExp) {
                $newContent = preg_replace($needle->regexp, "", $content); }
            else {
                $newContent = str_replace($needle, "", $content); }
            $this->write($newContent); }
        return $this;
    }

    public function contains($needle) {
        if ($needle instanceof RegExp) {
            return preg_match($needle->regexp, $this->read()); }
        else {
            return strstr($this->read(), $needle); }
    }

    public function append($str = null) {
        if (is_null($str)) {$str = $this->params["search"].PHP_EOL ;}
        $this->write($this->read() . $str);
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
        $string = ($oldline === null) ? $this->params["search"] : $oldline ;
        $newline = ($newline === null) ? $this->params["replace"] : $newline ;
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
        $string = ($line === null) ? $this->params["search"] : $line ;
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
        $string = ($line === null) ? $this->params["search"] : $line ;
        if (!($string instanceof RegExp)) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m"); }
        else {
            $searchString = $string; }
        if (substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n"; }
        if ($this->findString($searchString)) {
            $this->append($string . "\n"); }
        return $this;
    }

}