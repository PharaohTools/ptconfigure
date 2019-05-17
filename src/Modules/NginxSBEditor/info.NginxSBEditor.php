<?php

Namespace Info;

class NginxSBEditorInfo extends Base {

    public $hidden = false;

    public $name = "Nginx Server Block Functions";

    public function _construct() {
        parent::__construct();
    }

    public function routesAvailable() {
        return array( "NginxSBEditor" => array_merge(
            parent::routesAvailable(), array("add", "rm", "remove", "list", "enable", "en", "disable", "dis") ) );
    }

    public function routeAliases() {
        return array("nginxsbeditor"=>"NginxSBEditor", "nginx-sb-editor"=>"NginxSBEditor", "nginxsbe"=>"NginxSBEditor", "sbeditor"=>"NginxSBEditor");
    }

    public function helpDefinition() {
        $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Nginx ServerBlocks Functions.

  NginxSBEditor, nginx-sb-editor, nginxsbe, nginxsbeditor

          - add
          create a Server Block
          example: ptdeploy nginxsbe add
          sb-docroot
          sb-url
          sb-ip-port

          - rm
          remove a Server Block
          example: ptdeploy nginxsbe rm

          - list
          List current Server Blocks
          example: ptdeploy nginxsbe list

          - enable
          enable a Server Block
          example: ptdeploy nginxsbe enable

          - disable
          disable a Server Block
          example: ptdeploy nginxsbe disable

HELPDATA;
      return $help ;
    }


    public function generatorCodeInjection($step=null) {
        $inject = <<<'INJECT'
//
// // This function will set the ServerBlock template for your Server Block
// // You need to call this from your constructor
// private function calculateServerBlockDocRoot() {
        $serverAlias = str_replace("www", "*", $this->serverBlockEditorAdditionURL);
INJECT;
$inject .= "\n".'//   $this->steps['.$step.']["NginxSBEditor"]["serverBlockEditorAdditionDocRoot"] = getcwd();'."\n";
$inject .= <<<'INJECT'
// }
//
// // This function will set the ServerBlock template for your Server Block
// // You need to call this from your constructor
// private function setServerBlockTemplate() {
INJECT;
    $inject .= "\n".'//   $this->steps['.$step.']["NginxSBEditor"]["serverBlockEditorAdditionTemplateData"] = '."\n";
    $inject .= <<<'INJECT'
//  <<<'TEMPLATE'
//  NameserverBlock ****IP ADDRESS****:80
//  <serverBlock ****IP ADDRESS****:80>
//    ServerAdmin webmaster@localhost
// 	  ServerName ****SERVER NAME****
// 	  DocumentRoot ****WEB ROOT****/src
// 	  <Directory ****WEB ROOT****/src>
// 		  Options Indexes FollowSymLinks MultiViews
// 		  AllowOverride All
// 		  Order allow,deny
// 		  allow from all
// 	  </Directory>
//    ErrorLog /var/log/nginx/error.log
//    CustomLog /var/log/nginx/access.log combined
//  </serverBlock>
//
//  NameserverBlock ****IP ADDRESS****:443
//  <serverBlock ****IP ADDRESS****:443>
// 	  ServerAdmin webmaster@localhost
// 	  ServerName ****SERVER NAME****
// 	  DocumentRoot ****WEB ROOT****/src
//    # SSLEngine on
// 	  # SSLCertificateFile /etc/nginx/ssl/ssl.crt
//    # SSLCertificateKeyFile /etc/nginx/ssl/ssl.key
//    # SSLCertificateChainFile /etc/nginx/ssl/bundle.crt
// 	  <Directory ****WEB ROOT****/src>
// 		  Options Indexes FollowSymLinks MultiViews
//  		AllowOverride All
//		  Order allow,deny
//	  	allow from all
//  	</Directory>
//    ErrorLog /var/log/nginx/error.log
//    CustomLog /var/log/nginx/access.log combined
//  </serverBlock>
//TEMPLATE;
//}
//
INJECT;
        return $inject ;

    }

}