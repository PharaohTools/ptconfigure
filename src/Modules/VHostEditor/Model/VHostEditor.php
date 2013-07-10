<?php

Namespace Model;

class VhostEditor extends Base {

    private $vHostTemplate;
    private $docRoot;
    private $url;
    private $fileSuffix;
    private $vHostIp;
    private $vHostForDeletion;
    private $vHostEnabledDir;
    private $apacheCommand;
    private $vHostDir = '/etc/apache2/sites-available' ; // no trailing slash
    private $vHostTemplateDir;
    private $vHostDefaultTemplates;

    public function __construct() {
      $this->setVHostDefaultTemplates();
    }

    public function askWhetherToListVHost() {
        return $this->performVHostListing();
    }

    public function askWhetherToCreateVHost() {
        return $this->performVHostCreation();
    }

    public function askWhetherToDeleteVHost() {
        return $this->performVHostDeletion();
    }

    private function performVHostListing() {
        $this->vHostDir = $this->askForVHostDirectory();
        $this->vHostEnabledDir = $this->askForVHostEnabledDirectory();
        $this->listAllVHosts();
        return true;
    }

    private function performVHostCreation() {
        if ( !$this->askForVHostEntry() ) { return false; }
        $this->docRoot = $this->askForDocRoot();
        $this->url = $this->askForHostURL();
        $this->vHostIp = $this->askForVHostIp();
        $this->fileSuffix = $this->askForFileSuffix();
        $this->vHostTemplateDir = $this->askForVHostTemplateDirectory();
        $this->selectVHostTemplate();
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
        echo "Deleting vhost\n";
        $this->vHostDir = $this->askForVHostDirectory();
        $this->vHostForDeletion = $this->selectVHostInProjectOrFS();
        if ( self::areYouSure("Definitely delete VHost?") == false ) {
          return false; }
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
        echo "Creating vhost\n";
        $this->docRoot = $autoPilot->virtualHostEditorAdditionDocRoot;
        $this->url = $autoPilot->virtualHostEditorAdditionURL;
        $this->vHostIp = $autoPilot->virtualHostEditorAdditionIp;
        if ($autoPilot->virtualHostEditorAdditionTemplateData != null) {
            echo "Using autopilot vhost\n";
            $this->vHostTemplate = $autoPilot->virtualHostEditorAdditionTemplateData; }
        else { echo "Using default vhost\n";
            $this->setVhostDefaultTemplate(); }
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
      $question = 'What\'s the document root? Enter nothing for '.getcwd();
      $input = self::askForInput($question);
      return ($input=="") ? getcwd() : $input ;
    }

    private function askForHostURL(){
        $question = 'What URL do you want to add as server name?';
        return self::askForInput($question, true);
    }

    private function askForFileSuffix(){
        $question = 'What File Suffix should be used? Enter nothing for None (hint: ubuntu probably none centos, .conf)';
        $input = self::askForInput($question) ;
        return $input ;
    }

    private function askForApacheCommand(){
      $linuxTypeFromConfig = \Model\AppConfig::getAppVariable("linux-type") ;
        if ( in_array($linuxTypeFromConfig, array("debian", "redhat") ) ) {
          $input = ($linuxTypeFromConfig == "debian") ? "apache2" : "httpd" ; }
        else {
          $question = 'What is the service name of apache?';
          $input = self::askForArrayOption($question, array("apache2", "httpd"), true) ; }
        return $input ;
    }

    private function askForVHostIp(){
        $question = 'What IP:Port should be set? Enter nothing for 127.0.0.1:80';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1:80' : $input ;
    }

    private function checkVHostOkay(){
        $question = 'Please check VHost: '.$this->vHostTemplate."\n\nIs this Okay?";
        return self::askYesOrNo($question);
    }

    private function askForVHostDirectory(){
        $question = 'What is your VHost directory?';
        if ($this->detectApacheVHostFolderExistence()) { $question .= ' Found "/etc/apache2/sites-available" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostDir : $input ;  }
        if ($this->detectRHVHostFolderExistence()) { $question .= ' Found "/etc/httpd/vhosts.d" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? "/etc/httpd/vhosts.d" : $input ;  }
        return self::askForInput($question, true);
    }

    private function askForVHostEnabledDirectory(){
        $question = 'What is your Enabled/Available/Symlink VHost directory?';
        if ($this->detectVHostEnabledFolderExistence()) { $question .= ' Found "/etc/apache2/sites-enabled" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostDir : $input ;  }
        return self::askForInput($question, true);
    }

    private function askForVHostTemplateDirectory(){
        $question = 'What is your VHost Template directory? Enter nothing for default templates';
        if ($this->detectVHostTemplateFolderExistence()) {
            $question .= ' Found "'.$this->docRoot.'/build/config/dapperstrano/virtual-hosts" - use this?';
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostTemplateDir : $input ;  }
        else {
          $input = self::askForInput($question);
          return ($input=="") ? $this->vHostTemplateDir : $input ;
        }
    }

    private function detectApacheVHostFolderExistence(){
        return file_exists("/etc/apache2/sites-available");
    }

    private function detectRHVHostFolderExistence(){
        return file_exists("/etc/httpd/vhosts.d");
    }

    private function detectVHostEnabledFolderExistence(){
        return file_exists("/etc/apache2/sites-enabled");
    }

    private function detectVHostTemplateFolderExistence(){
        return file_exists( $this->vHostTemplateDir = $this->docRoot."/build/config/dapperstrano/virtual-hosts");
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
        $tmpDir = $this->baseTempDir.'/vhosttemp/';
        if (!file_exists($tmpDir)) {mkdir ($tmpDir);}
        return file_put_contents($tmpDir.'/'.$this->url, $this->vHostTemplate);
    }

    private function moveVHostAsRoot($virtualHostEditorAdditionFileSuffix=null){
        $command = 'sudo mv '.$this->baseTempDir.'/vhosttemp/'.$this->url.' '.$this->vHostDir.'/'.$this->url.$virtualHostEditorAdditionFileSuffix;
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
        $command = 'a2ensite '.$this->url;
        self::executeAndOutput($command, "a2ensite $this->url done");
        $vHostEnabledDir = (isset($vHostEditorAdditionSymLinkDirectory)) ?
            $vHostEditorAdditionSymLinkDirectory : str_replace("sites-available", "sites-enabled", $this->vHostDir );
        $command = 'sudo ln -s '.$this->vHostDir.'/'.$this->url.' '.$vHostEnabledDir.'/'.$this->url;
        return self::executeAndOutput($command, "VHost Enabled/Symlink Created if not done by a2ensite");
    }

    private function disableVHost(){
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'a2dissite '.$vHost;
            self::executeAndOutput($command, "a2dissite $vHost done");
            $command = 'sudo rm -f '.$this->vHostEnabledDir.'/'.$vHost;
            self::executeAndOutput($command, "VHost $vHost Disabled  if existed"); }
        return true;
    }

    private function restartApache(){
        echo "Restarting Apache...\n";
        $command = "sudo service $this->apacheCommand restart";
        return self::executeAndOutput($command);
    }

    private function checkIsDHProject() {
        return file_exists('dhproj');
    }

    private function listAllVHosts() {
        $projResults = ($this->checkIsDHProject()) ? \Model\AppConfig::getProjectVariable("virtual-hosts") : array() ;
        $enabledResults = scandir($this->vHostEnabledDir);
        $otherResults = scandir($this->vHostDir);
        $question = "Current Installed VHosts:\n";
        $i1 = $i2 = $i3 = 0;
        $availableVHosts = array();
        if (count($projResults)>0) {
            $question .= "--- Project Virtual Hosts: ---\n";
            foreach ($projResults as $result) {
                $question .= "($i1) $result\n";
                $i1++;
                $availableVHosts[] = $result;} }
        if (count($enabledResults)>0) {
            $question .= "--- Enabled Virtual Hosts: ---\n";
            foreach ($otherResults as $result) {
                if ($result === '.' or $result === '..') continue;
                $question .= "($i1) $result\n";
                $i1++;
                $availableVHosts[] = $result;} }
        if (count($otherResults)>0) {
            $question .= "--- All Available Virtual Hosts: ---\n";
            foreach ($otherResults as $result) {
                if ($result === '.' or $result === '..') continue;
                $question .= "($i1) $result\n";
                $i1++;
                $availableVHosts[] = $result;} }
        echo $question;
    }

    private function selectVHostInProjectOrFS(){
        $projResults = ($this->checkIsDHProject())
          ? \Model\AppConfig::getProjectVariable("virtual-hosts")
          : array() ;
        $otherResults = scandir($this->vHostDir);
        $question = "Please Choose VHost for Deletion:\n";
        $i1 = $i2 = 0;
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
        while ($validChoice == false) {
            if ($i2>0) { $question = "That's not a valid option, ".$question; }
            $input = self::askForInput($question) ;
            if ( array_key_exists($input, $availableVHosts) ){
                $validChoice = true;}
            $i2++; }
        return array($availableVHosts[$input]) ;
    }

    private function selectVHostTemplate(){
        $vHostTemplateResults = (is_array($this->vHostTemplateDir) &&
        count($this->vHostTemplateDir)>0)
          ? scandir($this->vHostTemplateDir)
          : array() ;
        $question = "Please Choose VHost Template: \n";
        $i1 = $i2 = 0;
        $availableVHostTemplates = array();
        $question .= "--- Default Virtual Host Templates: ---\n";
        foreach ($this->vHostDefaultTemplates as $title => $data) {
          $question .= "($i1) $title\n";
          $i1++;
          $availableVHostTemplates[] = $title; }
        if (count($vHostTemplateResults)>0) {
            $question .= "--- Virtual Host Templates in Project: ---\n";
            foreach ($vHostTemplateResults as $result) {
                if ($result === '.' or $result === '..') continue;
                $question .= "($i1) $result\n";
                $i1++;
                $availableVHostTemplates[] = $result;} }
        $validChoice = false;
        while ($validChoice == false) {
            if ($i2==1) { $question = "That's not a valid option, ".$question; }
            $input = self::askForInput($question) ;
            if (array_key_exists($input, $availableVHostTemplates) ){
                $validChoice = true;}
            $i2++; }
        if (array_key_exists($availableVHostTemplates[$input], $this->vHostDefaultTemplates) ) {
          $this->vHostTemplate
            = $this->vHostDefaultTemplates[$availableVHostTemplates[$input]];
          return ; }
      $this->vHostTemplate = file_get_contents($this->vHostTemplateDir . '/' .
        $availableVHostTemplates[$input]);
    }

    private function setVHostDefaultTemplates() {

      $template1 = <<<'TEMPLATE1'
NameVirtualHost ****IP ADDRESS****
<VirtualHost ****IP ADDRESS****>
	ServerAdmin webmaster@localhost
	ServerName ****SERVER NAME****
	DocumentRoot ****WEB ROOT****
	<Directory ****WEB ROOT****>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
TEMPLATE1;

      $template2 = <<<'TEMPLATE2'
NameVirtualHost ****IP ADDRESS****
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
TEMPLATE2;

      $template3 = <<<'TEMPLATE3'
NameVirtualHost ****IP ADDRESS****
<VirtualHost ****IP ADDRESS****>
	ServerAdmin webmaster@localhost
	ServerName ****SERVER NAME****
	DocumentRoot ****WEB ROOT****/web
	<Directory ****WEB ROOT****/web>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
TEMPLATE3;

        $template4 = <<<'TEMPLATE4'
NameVirtualHost ****IP ADDRESS****
<VirtualHost ****IP ADDRESS****>
	ServerAdmin webmaster@localhost
	ServerName ****SERVER NAME****
	DocumentRoot ****WEB ROOT****/www
	<Directory ****WEB ROOT****/www>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
TEMPLATE4;

        $template5 = <<<'TEMPLATE5'
NameVirtualHost ****IP ADDRESS****
<VirtualHost ****IP ADDRESS****>
	ServerAdmin webmaster@localhost
	ServerName ****SERVER NAME****
	DocumentRoot ****WEB ROOT****/docroot
	<Directory ****WEB ROOT****/docroot>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
TEMPLATE5;

    $this->vHostDefaultTemplates = array(
      "docroot-no-suffix" => $template1,
      "docroot-src-sfx" => $template2,
      "docroot-web-suffix" => $template3,
      "docroot-www-suffix" => $template4,
      "docroot-docroot-suffix" => $template5
    );

    }

}