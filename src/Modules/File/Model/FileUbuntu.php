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
            "should-have-line" => "performShouldHaveLine",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "File";
        $this->programDataFolder = "";
        $this->programNameMachine = "file"; // command and app dir name
        $this->programNameFriendly = "!File!!"; // 12 chars
        $this->programNameInstaller = "File";
        $this->initialize();
    }

    protected function performFileExistenceCheck() {
        $this->setFile();
        $this->setSearchLine();
        return $this->exists();
    }

    protected function performAppendLine() {
        $this->setFile();
        $this->setSearchLine();
        return $this->exists();
    }

    protected function performShouldHaveLine() {
        $this->setFile();
        $this->setSearchLine();
        return $this->exists();
    }

    public function setFile($fileName = null) {
        if (isset($fileName)) {
            $this->fileName = $fileName; }
        else if (isset($this->params["file"])) {
            $this->fileName = $this->params["file"]; }
        else if (isset($autopilot["file"])) {
            $this->fileName = $autopilot["file"]; }
        else {
            $this->fileName = self::askForInput("Enter File Path:", true); }
    }

    public function setSearchLine($searchLine = null) {
        if (isset($searchLine)) {
            $this->fileName = $searchLine; }
        else if (isset($this->params["searchline"])) {
            $this->fileName = $this->params["searchline"]; }
        else if (isset($autopilot["searchline"])) {
            $this->fileName = $autopilot["searchline"]; }
        else {
            $this->fileName = self::askForInput("Enter line of text to search for:", true); }
    }

    public function read() {
        return file_get_contents($this->fileName);
    }

    public function exists() {
        return file_exists($this->fileName);
    }

    public function write($content) {
        file_put_contents($this->fileName, $content);
        return $this;
    }

    public function replaceIfPresent($needle, $newNeedle) {
        if ($this->contains($needle)) {
            $content = $this->read();
            if ($needle instanceof RegExp) {
                $newContent = preg_replace($needle->regexp, $newNeedle, $content); }
            else {
                $newContent = str_replace($needle, $newNeedle, $content); }
            $this->write($newContent);
        }
        return $this;
    }

    public function contains($needle) {
        if ($needle instanceof RegExp) {
            return preg_match($needle->regexp, $this->read()); }
        else {
            return strstr($this->read(), $needle); }
    }

    public function append($newContent) {
        $this->write($this->read() . $newContent);
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
        if(!$this->contains($lines)) {
            $this->append($lines); }
    }

    public function shouldHaveLine($string) {
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

}