<?php

Namespace Model;

// @todo this class is way too long, we should use model groups, at least for balancing
// @todo  the vhosttemp folder that gets left in temp should be removed
class ApacheVHostEditorUbuntu14 extends ApacheVHostEditorUbuntuUpto13AndCentos {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("14.04", "14.10") ;
    public $architectures = array("64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params) ;
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
        if (isset($this->params["vhe-file-ext"])) { return $this->params["vhe-file-ext"] ; }
        if (isset($this->params["guess"])) { return ".conf" ; }
        $question = 'What File Extension should be used? Enter nothing for None (probably .conf on this system)';
        $input = self::askForInput($question) ;
        return $input ;
    }

}