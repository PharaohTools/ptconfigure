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
        $res = $this->symlinkRemover();
        if ($res == false) { return false ; }
        $res = $this->symlinkCreator();
        if ($res == false) { return false ; }
        $res = $this->removeDirectoriesToLimit() ;
        if ($res == false) { return false ; }
        return true ;
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
        if ( isset($this->params["version"])) { $version = $this->params["version"] ; }
        $otherResults = (is_dir($this->appRootDirectory)) ? scandir($this->appRootDirectory) : array();
        arsort($otherResults) ;
        $question = "Please Choose Version to make current (Showing newest first, Enter none for newest):\n";
        $i1 = 0;
        $availableVersions = array();
        if (count($otherResults)>0) {
            $question .= "--- All Versions: ---\n";
            foreach ($otherResults as $result) {
                if ($result === '.' || $result === '..' || $result === 'current') { continue ; }
                if (!is_dir($this->appRootDirectory.'/'.$result)) { continue ; }
 //               if (!is_int($result)) { continue ; }
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
        return ($input=="") ? getcwd() : $this->ensureTrailingSlash($input);
    }

    private function symlinkRemover() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $command  = 'rm -f '.$this->appRootDirectory.'current';
        $logging->log("Removing Version Symlink ".$this->appRootDirectory.'current', $this->getModuleName()) ;
        $rc = $this->executeAndGetReturnCode($command, false, true);
        if ($rc["rc"] == 0) {
            $logging->log("Successfully Removed Version Symlink", $this->getModuleName()) ;
            return true ; }
        $logging->log("Failed Removing Version Symlink", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
        return false ;
    }

    private function symlinkCreator() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $command  = 'ln -s '.$this->appRootDirectory.$this->appVersion.' '.$this->appRootDirectory.'current';
        $logging->log("Creating Version Symlink", $this->getModuleName()) ;
        $rc = $this->executeAndGetReturnCode($command, false, true);
        if ($rc["rc"] == 0) {
            $logging->log("Successfully Created Version Symlink", $this->getModuleName()) ;
            return true ; }
        $logging->log("Failed Creating Version Symlink", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
        return false ;    }

    private function removeDirectoriesToLimit() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ($this->versionLimit != null && $this->versionLimit>0) {
            $logging->log("Starting Versioning Limitation", $this->getModuleName()) ;
            $allEntries = (is_dir($this->appRootDirectory)) ? scandir($this->appRootDirectory) : array();
          arsort($allEntries) ;
          $i = 0;
//            $projectFactory = new \Model\Project();
//            $project = $projectFactory->getModel($this->params) ;
          foreach ($allEntries as $currentKey => $oneEntry) {
            $fullDirPath = $this->appRootDirectory.'/'.$oneEntry;
            if ( is_dir($fullDirPath) == null ) {
              unset ($allEntries[$currentKey]); } // remove entry from array if not directory
//            else if ($project::checkIsPharaohProject($fullDirPath) == false) {
//              unset ($allEntries[$currentKey]); } // remove entry from array if directory not a project
            else if ($oneEntry=="." || $oneEntry=="..") {
              unset ($allEntries[$currentKey]); }// remove entry from array if its dot notation
            $i++; }
          // now we have an array of all projects in directory
          $allEntries = array_reverse($allEntries, true);
          $i=1;
          $dirsToLeave = count($allEntries) - $this->versionLimit;
          foreach ($allEntries as &$oneEntry) {
            $fullDirPath = $this->appRootDirectory.$oneEntry;
            if ($i < $dirsToLeave) {
                $logging->log("Removing Project Directory $fullDirPath as Versioning Limitation", $this->getModuleName()) ;
                $this->deleteDirectory($fullDirPath); }
            $i++; }
            return true ; }
        else {
            $logging->log("Ignoring Versioning Limitation", $this->getModuleName()) ;
            return true ; }
    }

    private function deleteDirectory($fullDirPath) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $command  = 'rm -rf ' . $fullDirPath;
        $logging->log("Attempting to delete directory {$fullDirPath}", $this->getModuleName()) ;
        $rc = $this->executeAndGetReturnCode($command, false, true);
        if ($rc["rc"] == 0) {
            $logging->log("Successfully deleted directory", $this->getModuleName()) ;
            return true ; }
        $logging->log("Failed deleting directory", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
        return false ;
    }

}
