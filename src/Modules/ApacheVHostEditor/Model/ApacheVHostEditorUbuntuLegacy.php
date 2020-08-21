<?php

Namespace Model;

// @todo this class is way too long, we should use model groups, at least for balancing
// @todo  the vhosttemp folder that gets left in temp should be removed
class ApacheVHostEditorUbuntuLegacy extends Base {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("13.99", "-")) ;
    public $architectures = array("32", "64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $vHostTemplate;
    protected $docRoot;
    protected $url;
    protected $fileExtension;
    protected $vHostIp;
    protected $vHostForDeletion;
    protected $vHostEnabledDir;
    protected $apacheCommand;
    protected $vHostDir = '/etc/apache2/sites-available' ; // no trailing slash
    protected $vHostTemplateDir;
    protected $vHostDefaultTemplates;
    protected $vHostDefaultBalancerTemplates ;

    public function __construct($params) {
      parent::__construct($params);
      $this->setVHostDefaultTemplates();
    }

    public function askWhetherToListVHost() {
        return $this->performVHostListing();
    }

    public function askWhetherToCreateVHost() {
        return $this->performVHostCreation();
    }

    public function askWhetherToCreateBalancerVHost() {
        return $this->performBalancerVHostCreation();
    }

    public function askWhetherToDeleteVHost() {
        return $this->performVHostDeletion();
    }

    public function askWhetherToEnableVHost() {
        return $this->performVHostEnable();
    }

    public function askWhetherToDisableVHost() {
        return $this->performVHostDisable();
    }

    public function askWhetherToEnableDefaultVHost() {
        return $this->performVHostEnableDefault();
    }

    public function askWhetherToDisableDefaultVHost() {
        return $this->performVHostDisableDefault();
    }

    protected function performVHostListing() {
        $this->vHostDir = $this->askForVHostDirectory();
        $this->vHostEnabledDir = $this->findVHostEnabledDirectory();
        $this->listAllVHosts();
        return true;
    }

    protected function performVHostCreation() {
        if ( !$this->askForVHostEntry() ) { return false; }
        $this->fileExtension = $this->askForFileExtension();
        $this->docRoot = $this->askForDocRoot();
        $this->url = $this->askForHostURL();
        $this->vHostIp = $this->askForVHostIp();
        $this->vHostTemplateDir = $this->askForVHostTemplateDirectory();
        $this->selectVHostTemplate();
        $this->processVHost();
        if ( !$this->checkVHostOkay() ) { return false; }
        $this->vHostDir = $this->askForVHostDirectory();
        $this->attemptVHostWrite();
        if ( $this->askForEnableVHost() ) {
            $this->enableVHost(); }
        return true;
    }

    protected function performBalancerVHostCreation() {
        if ( !$this->askForVHostEntry(true) ) { return false; }
        $this->url = $this->askForHostURL();
        $this->vHostIp = $this->askForVHostIp();
        $this->fileExtension = $this->askForFileExtension();
        $this->vHostTemplateDir = $this->askForVHostTemplateDirectory();
        $this->setBalancerVHostTemplates()  ;
        $this->selectBalancerVHostTemplate();
        $this->processVHost();
        if ( !$this->checkVHostOkay() ) { return false; }
        $this->vHostDir = $this->askForVHostDirectory();
        $this->attemptVHostWrite();
        if ( $this->askForEnableVHost() ) {
            $this->enableVHost(); }
        return true;
    }

    protected function performVHostDeletion(){
        if ( !$this->askForVHostDeletion() ) { return false; }
        echo "Deleting vhost\n";
        $this->vHostDir = $this->askForVHostDirectory();
        $this->vHostForDeletion = $this->selectVHostInProjectOrFS();
        if ( $this->askForDisableVHost() ) {
            $this->disableVHost(); }
        $this->attemptVHostDeletion();
        return true;
    }

    protected function performVHostEnableDefault() {
        if ( $this->askForEnableVHost() ) {
            $this->url = '000-default.conf' ;
            $this->enableVHost();
            $this->url = 'default-ssl.conf' ;
            $this->enableVHost(); }
        return true;
    }

    protected function performVHostDisableDefault(){
        if ( $this->askForDisableVHost() ) {
            $this->vHostForDeletion = '000-default.conf' ;
            $this->disableVHost();
            $this->vHostForDeletion = 'default-ssl.conf' ;
            $this->disableVHost(); }
        return true;
    }

    protected function performVHostEnable() {
        if ( $this->askForEnableVHost("default") ) {
            $urlRay = $this->selectVHostInProjectOrFS() ;
            $this->url = $urlRay[0] ;
            $this->enableVHost(); }
        return true;
    }

    protected function performVHostDisable(){
        if ( $this->askForDisableVHost("default") ) {
            $this->vHostForDeletion = $this->selectVHostInProjectOrFS();
            $this->disableVHost(); }
        return true;
    }

    protected function askForVHostEntry($bal = null) {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $tx = ($bal == true) ? "Load Balancer " : "" ;
        $question = 'Do you want to add a '.$tx.'VHost?';
        return self::askYesOrNo($question);
    }

    protected function askForVHostDeletion() {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to delete VHost/s?';
        return self::askYesOrNo($question);
    }

    protected function askForEnableVHost($type = "") {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            if ($this->detectDebianApacheVHostFolderExistence()) {
                $logging->log("You have a sites available dir, guessing you need to enable a $type Virtual Host.", $this->getModuleName());
                return true ; }
            else {
                $logging->log("You don't have a sites available dir, guessing you don't need to enable a $type Virtual Host.", $this->getModuleName());
                return false ; } }
        $question = 'Do you want to enable this VHost? (hint - ubuntu probably yes, centos probably no)';
        return self::askYesOrNo($question);
    }

    protected function askForDisableVHost($type = "") {
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            if ($this->detectDebianApacheVHostFolderExistence()) {
                $logging->log("You have a sites available dir, guessing you need to disable a $type Virtual Host.", $this->getModuleName());
                return true ; }
            else {
                $logging->log("You don't have a sites available dir, guessing you don't need to disable a $type Virtual Host.", $this->getModuleName());
                return false ; } }
        $question = 'Do you want to disable this VHost? (hint - ubuntu probably yes, centos probably no)';
        return self::askYesOrNo($question);
    }

    protected function findVHostEnabledDirectory() {
        if ($this->detectDebianApacheVHostFolderExistence()) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("You have a sites available dir, so also listing available sites.", $this->getModuleName());
            return "/etc/apache2/sites-available" ; }
        return null ;
    }

    protected function askForDocRoot() {
        if (isset($this->params["vhe-docroot"])) { return $this->params["vhe-docroot"] ; }
        if (isset($this->params["guess"])) { return getcwd() ; }
        $question = 'What\'s the document root? Enter nothing for '.getcwd();
        $input = self::askForInput($question);
        return ($input=="") ? getcwd() : $input ;
    }

    protected function askForHostURL() {
        if (isset($this->params["vhe-url"])) { return $this->params["vhe-url"] ; }
        $question = 'What URL do you want to add as server name?';
        return self::askForInput($question, true);
    }

    protected function askForClusterName() {
        if (isset($this->params["vhe-cluster-name"])) { return $this->params["vhe-cluster-name"] ; }
        $question = 'What do you want to add as Cluster name?';
        return self::askForInput($question, true);
    }

    protected function askForFileExtension() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Setting a file extension for Older Ubuntu (14-)?", $this->getModuleName()) ;
        if (isset($this->params["vhe-file-ext"])) { return $this->params["vhe-file-ext"] ; }
        if (isset($this->params["guess"])) {
            if ($this->detectDebianApacheVHostFolderExistence()) { return null ; }
            else { return ".conf" ; } }
        $question = 'What File Extension should be used? Enter nothing for None (hint: ubuntu probably none centos, .conf)';
        $input = self::askForInput($question) ;
        return $input ;
    }

    protected function askForVHostIp() {
        if (isset($this->params["vhe-ip-port"])) { return $this->params["vhe-ip-port"] ; }
        if (isset($this->params["guess"])) { return "127.0.0.1:80" ; }
        $question = 'What IP:Port should be set? Enter nothing for 127.0.0.1:80';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1:80' : $input ;
    }

    protected function checkVHostOkay(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["yes"]) && $this->params["yes"]==true) {
            echo $this->vHostTemplate ;
            $logging->log("Assuming Okay due to yes parameter", $this->getModuleName());
            return true ;}
        $question = 'Please check VHost: '.$this->vHostTemplate."\n\nIs this Okay?";
        return self::askYesOrNo($question);
    }

    protected function askForVHostDirectory(){
        if (isset($this->params["vhe-vhost-dir"])) { return $this->params["vhe-vhost-dir"] ; }
        $question = 'What is your VHost directory?';
        if ($this->detectDebianApacheVHostFolderExistence()) { $question .= ' Found "/etc/apache2/sites-available" - Enter nothing to use this';
            if (isset($this->params["guess"])) { return $this->vHostDir ; }
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostDir : $input ;  }
        if ($this->detectRHVHostFolderExistence()) { $question .= ' Found "/etc/httpd/vhosts.d" - Enter nothing to use this';
            if (isset($this->params["guess"])) { return "/etc/httpd/vhosts.d" ; }
            $input = self::askForInput($question);
            return ($input=="") ? "/etc/httpd/vhosts.d" : $input ;  }
        return self::askForInput($question, true);
    }

    protected function askForVHostTemplateDirectory(){
        if (isset($this->params["vhe-default-template-name"])) { return "" ; }
        if (isset($this->params["vhe-template"])) { return "" ; }
        $question = 'What is your VHost Template directory? Enter nothing for default templates';
        if ($this->detectVHostTemplateFolderExistence()) {
            $question .= ' Found "'.$this->docRoot.'/build/config/ptdeploy/virtual-hosts" - Enter nothing to use this';
            $input = self::askForInput($question);
            return ($input=="") ? $this->vHostTemplateDir : $input ;  }
        else {
          $input = self::askForInput($question);
          return ($input=="") ? $this->vHostTemplateDir : $input ;
        }
    }

    protected function detectRHVHostFolderExistence(){
        return file_exists("/etc/httpd/vhosts.d");
    }

    protected function detectDebianApacheVHostFolderExistence(){
        return file_exists("/etc/apache2/sites-available");
    }

    protected function detectVHostEnabledFolderExistence(){
        return file_exists("/etc/apache2/sites-enabled");
    }

    protected function detectVHostTemplateFolderExistence(){
        return file_exists( $this->vHostTemplateDir = $this->docRoot."/build/config/ptdeploy/virtual-hosts");
    }

    protected function attemptVHostWrite($virtualHostEditorAdditionFileExtension=null){
        $this->createVHost();
        $this->moveVHostAsRoot($virtualHostEditorAdditionFileExtension);
        $this->writeVHostToProjectFile($virtualHostEditorAdditionFileExtension);
    }

    protected function attemptVHostDeletion(){
        $this->deleteVHostAsRoot();
        $this->deleteVHostFromProjectFile();
    }

    protected function processVHost() {
        // @todo do this with the templating module instead
        $replacements =  array('****WEB ROOT****'=>$this->docRoot,
            '****SERVER NAME****'=>$this->url, '****IP ADDRESS****'=>$this->vHostIp);
        $this->vHostTemplate = strtr($this->vHostTemplate, $replacements);
    }

    protected function createVHost() {
        $tmpDir = '/tmp'.DIRECTORY_SEPARATOR.'vhosttemp'.DIRECTORY_SEPARATOR;
        if (!file_exists($tmpDir)) {mkdir ($tmpDir, 0777, true);}
        return file_put_contents($tmpDir.'/'.$this->url.$this->fileExtension, $this->vHostTemplate);
    }

    protected function moveVHostAsRoot($virtualHostEditorAdditionFileExtension=null){
        $command = 'sudo mv '.'/tmp/'.DIRECTORY_SEPARATOR.'vhosttemp'.DIRECTORY_SEPARATOR.$this->url.$this->fileExtension.' '.
            $this->vHostDir.'/'.$this->url.$this->fileExtension;
        return self::executeAndOutput($command);
    }

    protected function deleteVHostAsRoot(){
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'sudo rm -f '.$this->vHostDir.'/'.$vHost;
            self::executeAndOutput($command, "VHost $vHost Deleted  if existed"); }
        return true;
    }

    protected function writeVHostToProjectFile($virtualHostEditorAdditionFileExtension=null){
        if ($this->checkIsDHProject()){
            \Model\AppConfig::setProjectVariable("virtual-hosts", $this->url.$virtualHostEditorAdditionFileExtension); }
    }

    protected function deleteVHostFromProjectFile(){
        if ($this->checkIsDHProject()){
            $allProjectVHosts = \Model\AppConfig::getProjectVariable("virtual-hosts");
            for ($i = 0; $i<=count($allProjectVHosts) ; $i++ ) {
                if (isset($allProjectVHosts[$i]) && in_array($allProjectVHosts[$i], $this->vHostForDeletion)) {
                    unset($allProjectVHosts[$i]); } }
            \Model\AppConfig::setProjectVariable("virtual-hosts", $allProjectVHosts); }
    }

    protected function enableVHost(){
        if (isset($this->params["vhe-file-ext"]) && strlen($this->params["vhe-file-ext"])>0 ) {
            $command = 'a2ensite '.$this->url.$this->params["vhe-file-ext"]; }
        else {
            $command = 'a2ensite '.$this->url ; }
        return self::executeAndOutput($command, "a2ensite $this->url done");
    }

    protected function disableVHost(){
        if (!is_array($this->vHostForDeletion)) {
            $this->vHostForDeletion = array($this->vHostForDeletion) ; }
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'a2dissite '.$vHost;
            self::executeAndOutput($command, "a2dissite $vHost done"); }
        return true;
    }

    protected function checkIsDHProject() {
        return file_exists('papyrusfile');
    }

    // @todo get project variable below is wrong
    // @todo look at how ugly this is
    protected function listAllVHosts() {
        $projResults = ($this->checkIsDHProject()) ? \Model\AppConfig::getProjectVariable("virtual-hosts") : array() ;
        $scanned = scandir($this->vHostEnabledDir) ;
        $enabledResults = (count($scanned)) ? $scanned : array() ;
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

    // @todo look at how ugly this is
    protected function selectVHostInProjectOrFS() {
        if (isset($this->params["vhost"])) { return array($this->params["vhost"]) ; }
        $projResults = ($this->checkIsDHProject())
          ? \Model\AppConfig::getProjectVariable("virtual-hosts")
          : array() ;
        $otherResults = scandir($this->vHostDir);
        $question = "Please Choose VHost:\n";
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

    // @todo, this is ugly and possibly unneccessary
    protected function selectVHostTemplate(){
        if (isset($this->params["vhe-template"])) {
            $this->vHostTemplate = $this->params["vhe-template"] ;
            return ; }
        $vHostTemplateResults = ( (is_array($this->vHostTemplateDir) && count($this->vHostTemplateDir)>0) )
          ? scandir($this->vHostTemplateDir)
          : array() ;
        if (isset($this->params["vhe-default-template-name"])) {
            $this->vHostTemplate = $this->vHostDefaultTemplates[$this->params["vhe-default-template-name"]] ;
            return ; }
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
                    $validChoice = true; }
                $i2++; }
        if (array_key_exists($availableVHostTemplates[$input], $this->vHostDefaultTemplates) ) {
          $this->vHostTemplate = $this->vHostDefaultTemplates[$availableVHostTemplates[$input]];
          return ; }
        $this->vHostTemplate = file_get_contents($this->vHostTemplateDir . '/' . $availableVHostTemplates[$input]);
    }

    protected function setVHostDefaultTemplates() {

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
            "docroot-src-suffix" => $template2,
            "docroot-web-suffix" => $template3,
            "docroot-www-suffix" => $template4,
            "docroot-docroot-suffix" => $template5
        );

    }

    // @todo, this is ugly and possibly unneccessary
    protected function selectBalancerVHostTemplate(){
        if (isset($this->params["vhe-template"])) {
            $this->vHostTemplate = $this->params["vhe-template"] ;
            return ; }
        else if (isset($this->params["vhe-default-template-name"])) {
            $this->vHostTemplate = $this->vHostDefaultBalancerTemplates[$this->params["vhe-default-template-name"]] ;
            return ; }
        else {
            $options = array_keys($this->vHostDefaultBalancerTemplates) ;
            $chosen = $this->askForArrayOption("Pick a Template", $options) ;
            $this->vHostTemplate = $this->vHostDefaultBalancerTemplates[$chosen] ;
            return ; }
    }

    public function setBalancerVHostTemplates() {

        $clusterName = $this->askForClusterName() ;
        $servers = $this->getServersText() ;

        $template1 = <<<"TEMPLATE1"

<Proxy balancer://$clusterName>

    # Define back-end servers:
    $servers
</Proxy>

<VirtualHost ****IP ADDRESS****>
    ServerAdmin webmaster@localhost
    ServerName ****SERVER NAME****
    ProxyPass / balancer://$clusterName/
</VirtualHost>

TEMPLATE1;

        $template2 = <<<"TEMPLATE2"

<Proxy balancer://$clusterName>

    # Define back-end servers:
    $servers
</Proxy>

<VirtualHost ****IP ADDRESS****>
	ServerAdmin webmaster@localhost
	ServerName ****SERVER NAME****
    ProxyPass / balancer://$clusterName/
</VirtualHost>

Listen 443

NameVirtualHost ****SSL_IP ADDRESS****
<VirtualHost ****SSL_IP ADDRESS****>
    SSLEngine On

    # Set the path to SSL certificate
    # Usage: SSLCertificateFile /path/to/cert.pem
    SSLCertificateFile ****SSL_CERT_FILE****
    # /etc/apache2/ssl/file.pem

    # Or, balance the load:
    ProxyPass / balancer://balancer_cluster_name

</VirtualHost>

TEMPLATE2;

        $this->vHostDefaultBalancerTemplates = array(
            "http" => $template1,
            "http-https" => $template2
        );

    }

    protected function askForEnvironment(){
        $question = 'What is the environment name of your web nodes? ';
        $input = self::askForInput($question, true);
        return $input ;
    }

    protected function getServersText() {
        $servers = $this->getServersArray() ;
        if ($servers != null) {
            $sText = "\n" ;
            $i = 0 ;
            foreach ($servers as $srvId => $srvDetails) {
                $sText .= '    # Server '.$srvId ;
                $sText .= (isset($srvDetails["name"])) ? ", {$srvDetails["name"]}" : "";
                $sText .= "\n";
                $sText .= '    BalancerMember http://'.$srvDetails["target"]."\n";
                $sText .= "\n";
                $i++ ; }
            return $sText ; }
        return false ;
    }

    protected function getServersArray() {
        $loggingFactory = new \Model\Logging() ;
        $log = $loggingFactory->getModel($this->params) ;
        if (isset($this->params["targets"])) {
            $log->log("Targets parameter provided, using these instead of Environments", $this->getModuleName()) ;
            $servers_raw = explode(',', $this->params["targets"]) ;
            $servers = [] ;
            foreach ($servers_raw as $server_target) {
                $one_server['name'] = '' ;
                $one_server['target'] = $server_target ;
                $servers[] = $one_server ;
            }
            return $servers ; }
        if (isset($this->params["env"])) {
            $this->params["environment-name"] = $this->params["env"] ; }
        if (!isset($this->params["environment-name"])) {
            $log->log("No environment name provided for Load Balancing", $this->getModuleName()) ;
            $this->params["environment-name"] = $this->askForEnvironment() ; }
        $envs = $this->getEnvironments();
        $names = $this->getEnvironmentNames($envs) ;
        $servers = $envs[$names[$this->params["environment-name"]]]["servers"];
        return $servers ;
    }

    protected function getEnvironmentNames($envs) {
        $eNames = array() ;
        foreach ($envs as $envKey => $env) {
            $envName = $env["any-app"]["gen_env_name"] ;
            $eNames[$envName] = $envKey ; }
        return $eNames ;
    }

    protected function getEnvironments() {
        return \Model\AppConfig::getProjectVariable("environments");
    }
}