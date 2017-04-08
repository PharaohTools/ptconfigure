<?php

Namespace Model;

class ApacheVHostEditorUbuntuModern extends ApacheVHostEditorUbuntuLegacy {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("14.00", "+")) ;
    public $architectures = array("32", "64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params) ;
    }

    public function enableVHost(){

        $this->params["vhe-file-ext"] = $this->askForFileExtension() ;
        if (isset($this->params["vhe-file-ext"]) && strlen($this->params["vhe-file-ext"])>0 ) {
            $command = 'a2ensite '.$this->url.$this->params["vhe-file-ext"]; }
        else {
            $command = 'a2ensite '.$this->url.".conf" ; }
        return self::executeAndOutput($command, "$command done");
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
    ProxyPassReverse / balancer://$clusterName/
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
    ProxyPassReverse / balancer://$clusterName/
</VirtualHost>

Listen 443

<VirtualHost ****SSL_IP ADDRESS****>
    SSLEngine On

    # Set the path to SSL certificate
    # Usage: SSLCertificateFile /path/to/cert.pem
    SSLCertificateFile ****SSL_CERT_FILE****
    # /etc/apache2/ssl/file.pem

    # Or, balance the load:
    ProxyPass / balancer://balancer_cluster_name/

</VirtualHost>

TEMPLATE2;

        $this->vHostDefaultBalancerTemplates = array(
            "http" => $template1,
            "http-https" => $template2
        );

    }

    protected function askForFileExtension() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if (isset($this->params["vhe-file-ext"]) && $this->params["vhe-file-ext"] !== "") {
            $logging->log("Setting forced file extension of '{$this->params["vhe-file-ext"]}'", $this->getModuleName()) ;
            return $this->params["vhe-file-ext"] ; }
        $logging->log("Setting a file extension for Modern Ubuntu (14+)?", $this->getModuleName()) ;
        if (isset($this->params["guess"])) {
            $logging->log("Guessing your VHost on Modern Ubuntu (14+) uses a .conf extension", $this->getModuleName()) ;
            return ".conf" ; }
        $question = 'What File Extension should be used? Enter nothing for None (probably .conf on this system)';
        $input = self::askForInput($question) ;
        return $input ;
    }

}