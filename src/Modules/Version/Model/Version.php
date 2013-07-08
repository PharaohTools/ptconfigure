<?php

Namespace Model;

class Version extends Base {

    private $appRootDirectory ;
    private $appVersion ;

    public function askWhetherToVersionSpecific($params){
        $this->setCmdLineParams($params);
        return $this->performSymLinkVersion();
    }

    public function askWhetherToVersionLatest($params){
        $this->setCmdLineParams($params);
        return $this->performSymLinkVersion("0");
    }

    public function askWhetherToVersionRollback($params){
        $this->setCmdLineParams($params);
        return $this->performSymLinkVersion("1");
    }

    private function performSymLinkVersion($arrayPointToRollback = null) {
        if ( !$this->askForSymLinkChange() ) { return false; }
        $this->appRootDirectory = $this->selectAppRoot();
        $this->appVersion = $this->selectAppVersion($arrayPointToRollback);
        $versionLimit = (isset($this->params["limit"])) ? $this->params["limit"] : $this->selectVersionLimit();
        $this->symlinkRemover();
        $this->symlinkCreator();
        $this->removeDirectoriesToLimit( $versionLimit ) ;
        return "Seems Fine...";
    }

    public function runAutoPilotVersion($autoPilot){
        if ( !$autoPilot->versionExecute ) { return false; }
        $this->appRootDirectory = $autoPilot->versionAppRootDirectory;
        $this->appVersion = $this->selectAppVersion($autoPilot->versionArrayPointToRollback);
        $this->symlinkRemover();
        $this->symlinkCreator();
        $this->removeDirectoriesToLimit( $autoPilot->versionLimit ) ;
        return true;
    }

    private function askForSymLinkChange(){
        $question = 'Do you want to change the version that *current* points to?';
        return self::askYesOrNo($question);
    }

    private function selectVersionLimit(){
        $question = 'How many Versions to limit to? Enter 0 to ignore version limits';
        return self::askForInteger($question);
    }

    private function selectAppVersion($version){
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

    private function selectAppRoot(){
        $question = 'What is the Application Root Directory? (The one with versions in) Enter none for '.getcwd();
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

    private function removeDirectoriesToLimit($versionLimit) {
        if ($versionLimit != null && $versionLimit>0) {
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
          $dirsToLeave = count($allEntries) - $versionLimit;
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