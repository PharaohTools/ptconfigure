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
        $vhe_ipport = (isset($this->params['vhe-ip-port'])) ? $this->params['vhe-ip-port'] : '127.0.0.1' ;

        $this->steps =
            array(

                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Configuration of a Pharaoh Build Web Interface"),),),

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
                    "vhe-ip-port" => $vhe_ipport,
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

        $dir_section = $this->getA2DirSection() ;
        $cgi_bin_dir = $this->getCGIBinDir() ;


        // @todo this should use above Require method for apache 2.4.7+ and below allow all for less

        $template ='
 NameVirtualHost ****IP ADDRESS****:80
 <VirtualHost ****IP ADDRESS****:80>
   ServerAdmin webmaster@localhost
 	ServerName ****SERVER NAME****
 	DocumentRoot ****WEB ROOT****
 	<Directory ****WEB ROOT****>
 	'. $dir_section .'
 	</Directory>
   ErrorLog /var/log/apache2/error.log
   CustomLog /var/log/apache2/access.log combined

   <IfModule mod_fastcgi.c>
     AddType application/x-httpd-fastphp5 .php
     Action application/x-httpd-fastphp5 /php5-fcgi
     Alias /php5-fcgi '.$cgi_bin_dir.'php5-fcgi_ptbuild
     FastCgiExternalServer '.$cgi_bin_dir.'php5-fcgi_ptbuild -socket /var/run/php5-fpm_ptbuild.sock -pass-header Authorization
   </IfModule>

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
' ;

        return $template ;
    }

    private function getA2DirSection() {
        $comm = 'ptconfigure apacheserver version -yg' ;
        exec($comm, $output) ;
        foreach ($output as $outline) {
            $spos = strpos($outline, "Short Version: ") ;
            $lpos = $spos+15 ;
            $rpos = strpos($outline, $lpos, "\n");
            if ($spos !== false) {
                $sv = substr($outline,$lpos, $rpos) ;  } }

        $svObject = new \Model\SoftwareVersion($sv) ;
        $compareObject = new \Model\SoftwareVersion("2.3.0") ;

        if ($svObject->isLessThan($compareObject)) {
            $section = '
            Options Indexes FollowSymLinks MultiViews
            Order allow,deny
            Allow from all' ; }
        else {
            $section = '
            Options Indexes FollowSymLinks MultiViews
            Require all granted' ; }
        return $section ;
    }

    private function getCGIBinDir() {
        $system = new \Model\SystemDetection();
        $sys = $system->getModel($this->params);
        if (in_array("Darwin", $sys->os)) { $cgi_bin_dir = "/usr/lib/cgi-bin/" ; }
        else if (in_array("Linux", $sys->os) && in_array("Ubuntu", $sys->distros)) { $cgi_bin_dir = "/usr/lib/cgi-bin/" ; }
        else if (in_array("Linux", $sys->os) && in_array("Debian", $sys->distros)) { $cgi_bin_dir = "/usr/lib/cgi-bin/" ; }
        else if (in_array("Linux", $sys->os) && in_array("CentOS", $sys->distros)) { $cgi_bin_dir = "/usr/lib/cgi-bin/" ; }
        else { $cgi_bin_dir = "/usr/lib/cgi-bin/" ; }
        return $cgi_bin_dir ;
    }






}
