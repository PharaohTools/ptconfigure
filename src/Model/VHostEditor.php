<?php

Namespace Model;

class VhostEditor extends Base {

    private $vHostTemplate ;
    private $docRoot ;
    private $url ;
    private $fileSuffix ;
    private $vHostIp ;
    private $vHostForDeletion ;
    private $vHostEnabledDir;
    private $apacheCommand;
    private $vHostDir = '/etc/apache2/sites-available'; // no trailing slash

    public function __construct(){
        $this->setVhostTemplate();
    }

    public function askWhetherToCreateVHost(){
        return $this->performVHostCreation();
    }

    public function askWhetherToDeleteVHost(){
        return $this->performVHostDeletion();
    }

    private function performVHostCreation(){
        if ( !$this->askForVHostEntry() ) { return false; }
        $this->docRoot = $this->askForDocRoot();
        $this->url = $this->askForHostURL();
        $this->vHostIp = $this->askForVHostIp();
        $this->fileSuffix = $this->askForFileSuffix();
        $this->processVHost();
        if ( !$this->checkVHostOkay() ) { return false; }
        $this->vHostDir = $this->askForVHostDirectory();
        $this->attemptVHostWrite();
        if ( $this->askForEnableVHost() ) {
            $this->vHostEnabledDir = $this->askForVHostEnabledDirectory();
            $this->enableVHost(); }
        $this->apacheCommand = $this->askForApacheCommand();
        $this->restartApache();
        return true;
    }

    private function performVHostDeletion(){
        if ( !$this->askForVHostDeletion() ) { return false; }
        $this->vHostDir = $this->askForVHostDirectory();
        $this->vHostForDeletion = $this->selectVHostInProjectOrFS();
        if ( !self::areYouSure("Definitely delete VHost?") ) { return false; }
        if ( $this->askForDisableVHost() ) {
            $this->vHostEnabledDir = $this->askForVHostEnabledDirectory();
            $this->disableVHost(); }
        $this->attemptVHostDeletion();
        $this->apacheCommand = $this->askForApacheCommand();
        $this->restartApache();
        return true;
    }

    public function runAutoPilotVHostCreation($autoPilot){
        if ( !$autoPilot->virtualHostEditorAdditionExecute ) { return false; }
        $this->docRoot = $autoPilot->virtualHostEditorAdditionDocRoot;
        $this->url = $autoPilot->virtualHostEditorAdditionURL;
        $this->vHostIp = $autoPilot->virtualHostEditorAdditionIp;
        $this->processVHost();
        $this->vHostDir = $autoPilot->virtualHostEditorAdditionDirectory;
        $this->attemptVHostWrite($autoPilot->virtualHostEditorAdditionFileSuffix);
        if ( $autoPilot->virtualHostEditorAdditionVHostEnable==true ) {
            $this->enableVHost($autoPilot->virtualHostEditorAdditionSymLinkDirectory); }
        $this->apacheCommand = $autoPilot->virtualHostEditorAdditionApacheCommand;
        $this->restartApache();
        return true;
    }

    public function runAutoPilotVHostDeletion($autoPilot) {
        if ( !$autoPilot->virtualHostEditorDeletionExecute ) { return false; }
        $this->vHostDir = $autoPilot->virtualHostEditorDeletionDirectory;
        $this->vHostForDeletion = $autoPilot->virtualHostEditorDeletionTarget;
        if ( $autoPilot->virtualHostEditorDeletionVHostDisable==true ) {
            $this->disableVHost($autoPilot->virtualHostEditorDeletionSymLinkDirectory); }
        $this->attemptVHostDeletion();
        $this->apacheCommand = $autoPilot->virtualHostEditorDeletionApacheCommand;
        $this->restartApache();
        return true;
    }

    private function askForVHostEntry(){
        $question = 'Do you want to add a VHost?';
        return self::askYesOrNo($question);
    }

    private function askForVHostDeletion(){
        $question = 'Do you want to delete VHost/s?';
        return self::askYesOrNo($question);
    }

    private function askForEnableVHost(){
        $question = 'Do you want to enable this VHost? (hint - ubuntu probably yes, centos probably no)';
        return self::askYesOrNo($question);
    }

    private function askForDisableVHost(){
        $question = 'Do you want to disable this VHost? (hint - ubuntu probably yes, centos probably no)';
        return self::askYesOrNo($question);
    }

    private function askForDocRoot(){
        $question = 'What\'s the document root?';
        return self::askForInput($question, true);
    }

    private function askForHostURL(){
        $question = 'What URL do you want to add as server name?';
        return self::askForInput($question, true);
    }

    private function askForFileSuffix(){
        $question = 'What File Suffix should be used? Enter for None (hint: ubuntu probably none centos, .conf)';
        $input = self::askForInput($question) ;
        return $input ;
    }

    private function askForApacheCommand(){
        $question = 'What is the service name of apache? Enter for apache2. (hint: ubuntu - apache2, centos- httpd)';
        $input = self::askForInput($question) ;
        return ($input=="") ? 'apache2' : $input ;
    }

    private function askForVHostIp(){
        $question = 'What IP should be set? Enter for 127.0.0.1';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1' : $input ;
    }

    private function checkVHostOkay(){
        $question = 'Please check VHost: '.$this->vHostTemplate."\n\nIs this Okay? () Y/N";
        return self::askYesOrNo($question);
    }

    private function askForVHostDirectory(){
        $question = 'What is your VHost directory?';
        if ($this->detectVHostFolderExistence()) { $question .= ' Found "/etc/apache2/sites-available" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostDir : $input ;  }
        return self::askForInput($question, true);
    }

    private function askForVHostEnabledDirectory(){
        $question = 'What is your Enabled/Available/Symlink VHost directory?';
        if ($this->detectVHostEnabledFolderExistence()) { $question .= ' Found "/etc/apache2/sites-enabled" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostDir : $input ;  }
        return self::askForInput($question, true);
    }

    private function detectVHostFolderExistence(){
        return file_exists($this->vHostDir);
    }

    private function detectVHostEnabledFolderExistence(){
        return file_exists("/etc/apache2/sites-enabled");
    }

    private function attemptVHostWrite($virtualHostEditorAdditionFileSuffix=null){
        $this->createVHost();
        $this->moveVHostAsRoot($virtualHostEditorAdditionFileSuffix);
        $this->writeVHostToProjectFile($virtualHostEditorAdditionFileSuffix);
    }

    private function attemptVHostDeletion(){
        $this->deleteVHostAsRoot();
        $this->deleteVHostFromProjectFile();
    }

    private function processVHost() {
        $replacements =  array('****WEB ROOT****'=>$this->docRoot,
            '****SERVER NAME****'=>$this->url, '****IP ADDRESS****'=>$this->vHostIp);
        $this->vHostTemplate = strtr($this->vHostTemplate, $replacements);
    }

    private function createVHost() {
        $tmpDir = '/tmp/vhosttemp/';
        if (!file_exists($tmpDir)) {mkdir ($tmpDir);}
        return file_put_contents($tmpDir.'/'.$this->url, $this->vHostTemplate);
    }

    private function moveVHostAsRoot($virtualHostEditorAdditionFileSuffix=null){
        $command = 'sudo mv /tmp/vhosttemp/'.$this->url.' '.$this->vHostDir.'/'.$this->url.$virtualHostEditorAdditionFileSuffix;
        return self::executeAndOutput($command);
    }

    private function deleteVHostAsRoot(){
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'sudo rm -f '.$this->vHostDir.'/'.$vHost;
            self::executeAndOutput($command, "VHost $vHost Deleted  if existed"); }
        return true;
    }

    private function writeVHostToProjectFile($virtualHostEditorAdditionFileSuffix=null){
        if ($this->checkIsDHProject()){
            \Model\AppConfig::setProjectVariable("virtual-hosts", $this->url.$virtualHostEditorAdditionFileSuffix); }
    }

    private function deleteVHostFromProjectFile(){
        if ($this->checkIsDHProject()){
            $allProjectVHosts = \Model\AppConfig::getProjectVariable("virtual-hosts");
            for ($i = 0; $i<=count($allProjectVHosts) ; $i++ ) {
                if (isset($allProjectVHosts[$i]) && in_array($allProjectVHosts[$i], $this->vHostForDeletion)) {
                    unset($allProjectVHosts[$i]); } }
            \Model\AppConfig::setProjectVariable("virtual-hosts", $allProjectVHosts); }
    }

    private function enableVHost($vHostEditorAdditionSymLinkDirectory=null){
        $vHostEnabledDir = (isset($vHostEditorAdditionSymLinkDirectory)) ?
            $vHostEditorAdditionSymLinkDirectory : str_replace("sites-available", "sites-enabled", $this->vHostDir );
        $command = 'sudo ln -s '.$this->vHostDir.'/'.$this->url.' '.$vHostEnabledDir.'/'.$this->url;
        return self::executeAndOutput($command, "VHost Enabled/Symlink Created");
    }

    private function disableVHost(){
        $vHostEnabledDir = str_replace("sites-available", "sites-enabled", $this->vHostDir );
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'sudo rm -f '.$vHostEnabledDir.'/'.$vHost;
            self::executeAndOutput($command, "VHost $vHost Disabled  if existed"); }
        return true;
    }

    private function restartApache(){
        $command = "sudo service $this->apacheCommand restart";
        return self::executeAndOutput($command);
    }

    private function checkIsDHProject() {
        return file_exists('dhproj');
    }

    private function selectVHostInProjectOrFS(){
        $projResults = ($this->checkIsDHProject()) ? \Model\AppConfig::getProjectVariable("virtual-hosts") : array() ;
        $otherResults = scandir($this->vHostDir);
        $question = "Please Choose VHost for Deletion:\n";
        $i1 = $i2 = $i3 = 0;
        $availableVHosts = array();
        if (count($projResults)>0) {
            $question .= "--- Project Virtual Hosts: ---\n";
            foreach ($projResults as $result) {
                $question .= "($i1) $result\n";
                $i1++;
                $availableVHosts[] = $result;} }
        if (count($otherResults)>0) {
            $question .= "--- All Virtual Hosts: ---\n";
            foreach ($otherResults as $result) {
                if ($result === '.' or $result === '..') continue;
                $question .= "($i1) $result\n";
                $i1++;
                $availableVHosts[] = $result;} }
        $validChoice = false;
        $i=0;
        while ($validChoice == false) {
            if ($i==1) { $question = "That's not a valid option, ".$question; }
            $input = self::askForInput($question) ;
            if ( array_key_exists($input, $availableVHosts) ){
                $validChoice = true;}
            $i++; }
        return array($availableVHosts[$input]) ;
    }

    private function setVhostTemplate() {
        $this->vHostTemplate = <<<'TEMPLATE'
<VirtualHost ****IP ADDRESS****>
	ServerAdmin webmaster@localhost
	ServerName ****SERVER NAME****
	DocumentRoot ****WEB ROOT****/src
	<Directory ****WEB ROOT****/src>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
TEMPLATE;
    }


}