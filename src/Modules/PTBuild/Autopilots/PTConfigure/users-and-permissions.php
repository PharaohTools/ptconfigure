<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    public function __construct($params = null) {
        parent::__construct($params);
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        $vhe_url = (isset($this->params['vhe-url'])) ? $this->params['vhe-url'] : 'build.pharaoh.tld' ;

        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Reverse Proxy from 8080 to 80"),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Create Host file entry for $vhe_url", ), ), ),
                array ( "HostEditor" => array( "add" => array(
                    "guess" => true,
                    "host-name" => $vhe_url,
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Disable default Apache vhost", ), ), ),
                array ( "ApacheVHostEditor" => array( "disable-default" => array( "guess" => true, ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Lets Add our Pharaoh Build VHost" ),),),
                array ( "ApacheVHostEditor" => array( "add" => array(
                    "vhe-docroot" => PFILESDIR.'ptbuild'.DS.'ptbuild'.DS.'src'.DS.'Modules'.DS.'PostInput'.DS,
                    "guess" => true,
                    "vhe-url" => $vhe_url,
                    "vhe-ip-port" => "127.0.0.1",
//                    "vhe-vhost-dir" => "/etc/apache2/sites-available",
                    "vhe-template" => $this->getTemplate(),
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Now lets restart Apache so we are serving our new proxy", ), ), ),
                array ( "ApacheControl" => array( "restart" => array( "guess" => true, ), ), ),

                // End
                array ( "Logging" => array( "log" => array( "log-message" => "Apache Web Server for Pharaoh Build Complete"),),),

            );

    }

    private function getTemplate() {
//        $apacheFactory = new \Model\ApacheServer();
//        $apache = $apacheFactory->getModel($this->params) ;
//        $av = $apache->getVersion("Installed");
//
//        var_dump($av);

        // @todo this should use above Require method for apache 2.4.7+ and below allow all for less

        $template =
            <<<'TEMPLATE'
           NameVirtualHost ****IP ADDRESS****:80
 <VirtualHost ****IP ADDRESS****:80>
   ServerAdmin webmaster@localhost
 	ServerName ****SERVER NAME****
 	DocumentRoot ****WEB ROOT****
 	<Directory ****WEB ROOT****>
 		Options Indexes FollowSymLinks MultiViews
        Require all granted
 	</Directory>
   ErrorLog /var/log/apache2/error.log
   CustomLog /var/log/apache2/access.log combined
 </VirtualHost>

# NameVirtualHost ****IP ADDRESS****:443
# <VirtualHost ****IP ADDRESS****:443>
# 	 ServerAdmin webmaster@localhost
# 	 ServerName ****SERVER NAME****
# 	 DocumentRoot ****WEB ROOT****
  # SSLEngine on
 	 # SSLCertificateFile /etc/apache2/ssl/ssl.crt
   # SSLCertificateKeyFile /etc/apache2/ssl/ssl.key
   # SSLCertificateChainFile /etc/apache2/ssl/bundle.crt
# 	 <Directory ****WEB ROOT****>
# 		 Options Indexes FollowSymLinks MultiViews
#		AllowOverride All
#		Order allow,deny
#		allow from all
#	</Directory>
#  ErrorLog /var/log/apache2/error.log
#  CustomLog /var/log/apache2/access.log combined
#  </VirtualHost>
TEMPLATE;

        return $template ;
    }



}
