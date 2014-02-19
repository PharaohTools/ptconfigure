<?php

Namespace Model;

class FileUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    protected $fileName ;
    protected $actionsToMethods =
        array(
            // "create" => "performFileCreate",
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

//    protected function performFileRead() {
//        $this->setFile();
//        return $this->create();
//    }
//
//    protected function performFileSetPassword() {
//        $this->setFile();
//        $this->setPassword();
//    }
//
//    protected function performFileRemove() {
//        $this->setFile();
//        $result = $this->remove();
//        return $result ;
//    }
//
//    protected function performShowGroups() {
//        $this->setFile();
//        $result = $this->getGroups();
//        return $result ;
//    }
//
//    protected function performFileAddToGroup() {
//        $this->setFile();
//        $result = $this->addToGroup();
//        return $result ;
//    }
//
//    protected function performFileRemoveFromGroup() {
//        $this->setFile();
//        $result = $this->removeFromGroup();
//        return $result ;
//    }
//
    protected function performFileExistenceCheck() {
        $this->setFile();
        return $this->exists();
    }

    public function setFile($fileName = null) {
        if (isset($fileName)) {
            $this->fileName = $fileName; }
        else if (isset($this->params["filename"])) {
            $this->fileName = $this->params["filename"]; }
        else if (isset($autopilot["filename"])) {
            $this->fileName = $autopilot["filename"]; }
        else {
            $this->fileName = self::askForInput("Enter Filename:", true); }
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
                $newContent = preg_replace($needle->regexp, $newNeedle, $content);
            } else {
                $newContent = str_replace($needle, $newNeedle, $content); }
            $this->write($newContent);
        }
        return $this;
    }

    public function contains($needle)
    {
        if ($needle instanceof RegExp) {
            return preg_match($needle->regexp, $this->read());
        } else {
            return strstr($this->read(), $needle);
        }
    }

    public function append($newContent)
    {
        $this->write($this->read() . $newContent);
    }

    public function chmod($string)
    {
        chmod($this->fileName, $string);
    }

    public function findString($needle)
    {
        if ($needle instanceof RegExp) {
            preg_match_all($needle->regexp, $this->read(), $m);
            if (isset($m[1])) {
                return $m[1];
            }
            if (isset($m[0])) {
                return $m[0];
            }
        } else {
            if (strstr($this->read(), $needle)) {
                return $needle;
            };
        }
        return null;
    }

    public function shouldHaveLines($lines)
    {
        if(!$this->contains($lines)) {
            $this->append($lines);
        }
    }

    public function shouldHaveLine($string)
    {
        if (!($string instanceof RegExp)) {
            $searchString = new RegExp("/^" . rtrim(str_replace('/', '\\/', preg_quote($string))) . "$/m");
        } else {
            $searchString = $string;
        }

        if(substr($searchString, -1, 1) != "\n") {
            $searchString .= "\n";
        }

        if (!$this->findString($searchString)) {
            $this->append($string . "\n");
        }

        return $this;
    }


}