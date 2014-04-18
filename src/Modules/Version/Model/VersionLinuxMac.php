<?php

Namespace Model;

class VersionLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $appRootDirectory ;
    private $appVersion ;
    private $versionLimit ;

    public function askWhetherToVersionSpecific() {
        return $this->performSymLinkVersion();
    }

    public function askWhetherToVersionLatest() {
        $this->params["version"] = 0 ;
        return $this->performSymLinkVersion();
    }

    public function askWhetherToVersionRollback() {
        $this->params["version"] = 1 ;
        return $this->performSymLinkVersion();
    }

    private function performSymLinkVersion() {
        if ( !$this->askForSymLinkChange() ) { return false; }
        $this->appRootDirectory = $this->selectAppRoot();
        $this->appVersion = $this->selectAppVersion();
        $this->versionLimit = $this->selectVersionLimit();
        $this->symlinkRemover();
        $this->symlinkCreator();
        $this->removeDirectoriesToLimit() ;
        return "Seems Fine...";
    }

    private function askForSymLinkChange() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to change the version that *current* points to?';
        return self::askYesOrNo($question);
    }

    private function selectVersionLimit() {
        if ( isset($this->params["limit"])) { return $this->params["limit"] ; }
        $question = 'How many Versions to limit to? Enter 0 to ignore version limits';
        return self::askForInteger($question);
    }

    private function selectAppVersion() {
        if ( isset($this->params["version"])) { return $this->params["version"] ; }
        $otherResults = (is_dir($this->appRootDirectory)) ? scandir($this->appRootDirectory) : array();
        arsort($otherResults) ;
        $question = "Please Choose Version to make current (Showing newest first, Enter none for newest):\n";
        $i1 = 0;
        $availableVersions = array();
        if (count($otherResults)>0) {
            $question .= "--- All Versions: ---\n";
            foreach ($otherResults as $result) {
                if ($result === '.' || $result === '..' || $result === 'current') continue;
                if (!is_dir($this->appRootDirectory.'/'.$result)) continue;
                $question .= "($i1) $result\n";
                $i1++;
                $availableVersions[] = $result;} }
        if (isset($version) ) { return $availableVersions[$version] ; }
        else {
            $validChoice = false;
            while ($validChoice == false) {
                $input = self::askForInput($question) ;
                if ( array_key_exists($input, $availableVersions) ){
                    $validChoice = true;} }
            return $availableVersions[$input] ; }
    }

    private function selectAppRoot() {
        if ( isset($this->params["container"])) { return $this->params["container"] ; }
        $question = 'What is the Project Container Directory? (The one with versions in) Enter none for '.getcwd();
        $input = self::askForInput($question) ;
        return ($input=="") ? getcwd() : $input ;
    }

    private function symlinkRemover() {
        $command  = 'rm -f '.$this->appRootDirectory.'/current';
        echo "Removed Version Symlink\n";
        self::executeAndOutput($command);
    }

    private function symlinkCreator() {
        $command  = 'ln -s '.$this->appRootDirectory.'/'.$this->appVersion.' '.$this->appRootDirectory.'/current';
        echo "Created Version Symlink\n";
        self::executeAndOutput($command);
    }

    private function removeDirectoriesToLimit() {
        if ($this->versionLimit != null && $this->versionLimit>0) {
          $allEntries = (is_dir($this->appRootDirectory)) ? scandir($this->appRootDirectory) : array();
          arsort($allEntries) ;
          $i = 0;
          foreach ($allEntries as $currentKey => $oneEntry) {
            $fullDirPath = $this->appRootDirectory.'/'.$oneEntry;
            if ( is_dir($fullDirPath) == null ) {
              unset ($allEntries[$currentKey]); } // remove entry from array if not directory
            else if (\Model\Project::checkIsDHProject($fullDirPath) == false) {
              unset ($allEntries[$currentKey]); } // remove entry from array if directory not a project
            else if ($oneEntry=="." || $oneEntry=="..") {
              unset ($allEntries[$currentKey]); }// remove entry from array if its dot notation
            $i++; }
          // now we have an array of all projects in directory
          $allEntries = array_reverse($allEntries, true);
          $i=1;
          $dirsToLeave = count($allEntries) - $this->versionLimit;
          foreach ($allEntries as &$oneEntry) {
            $fullDirPath = $this->appRootDirectory.'/'.$oneEntry;
            if ($i < $dirsToLeave) {
              $this->deleteDirectory($fullDirPath);
              echo "Removing Project Directory $fullDirPath as Versioning Limitation\n"; }
            $i++; } }
        else {
          echo "Ignoring Versioning Limitation\n"; }
    }

    private function deleteDirectory($fullDirPath) {
        system('rm -rf ' . $fullDirPath, $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

}