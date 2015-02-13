<?php

Namespace Info;

class ApacheVHostEditorInfo extends Base {

    public $hidden = false;

    public $name = "Apache Virtual Host Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheVHostEditor" => array_merge(parent::routesAvailable(), array("add", "add-balancer", "rm",
          "remove", "list", "enable", "en", "disable", "dis") ) );
    }

    public function routeAliases() {
      return array("vhe"=>"ApacheVHostEditor", "vhosted"=>"ApacheVHostEditor", "vhed"=>"ApacheVHostEditor",
          "vhosteditor"=>"ApacheVHostEditor", "VHostEditor"=>"ApacheVHostEditor",  "apachevhosteditor"=>"ApacheVHostEditor");
    }

    // @todo finish this
    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Apache VHosts Functions.

  ApacheVHostEditor, apachevhosteditor, vhosteditor, vhe, vhosted

          - add
          create a Virtual Host
          example: sudo ptdeploy vhe add
          example: sudo ptdeploy vhe add --yes --vhe-docroot=/var/www/the-app --vhe-url=www.dave.com --vhe-file-ext="" --vhe-apache-command="apache2" --vhe-ip-port="127.0.0.1:80" --vhe-vhost-dir="/etc/apache2/sites-available" --vhe-template="*template data*"
          example: sudo ptdeploy vhe add --yes --guess --vhe-url=www.dave.com
              # will attempt to guess the following but you can override any
              # --vhe-docroot=*current working dir*
              # --vhe-file-ext="ubuntu none, others .conf"
              # --vhe-apache-command="apache2 or httpd depends on system"
              # --vhe-ip-port="127.0.0.1:80"
              # --vhe-vhost-dir="/etc/apache2/sites-available or /etc/httpd/vhosts.d"
              # --vhe-template="*template data*"
              # --vhe-default-template-name="docroot-src-suffix" // from default templates

          - add-balancer
          create a Virtual Host
          example: sudo ptdeploy vhe add
          example: sudo ptdeploy vhe add --yes --vhe-docroot=/var/www/the-app --vhe-url=www.dave.com --vhe-file-ext="" --vhe-apache-command="apache2" --vhe-ip-port="127.0.0.1:80" --vhe-vhost-dir="/etc/apache2/sites-available" --vhe-template="*template data*"
          example: sudo ptdeploy vhe add --yes --guess --vhe-url=www.dave.com
              # will attempt to guess the following but you can override any
              # --vhe-docroot=*current working dir*
              # --vhe-file-ext="ubuntu none, others .conf"
              # --vhe-apache-command="apache2 or httpd depends on system"
              # --vhe-ip-port="127.0.0.1:80"
              # --vhe-vhost-dir="/etc/apache2/sites-available or /etc/httpd/vhosts.d"
              # --vhe-template="*template data*"
              # --vhe-default-template-name="docroot-src-suffix" // from default templates

          - rm
          example: ptdeploy vhe rm
          example: ptdeploy vhe rm --yes --
          example: ptdeploy vhe rm --yes --guess --vhe-deletion-vhost=www.site.com
          example: ptdeploy vhe rm --yes --guess --vhe-deletion-vhost=www.site.com

          - list
          List current Virtual Hosts
          example: ptdeploy vhe list

          - enable
          enable a Server Block
          example: ptdeploy vhe enable

          - disable
          disable a Server Block
          example: ptdeploy vhe disable

HELPDATA;
      return $help ;
    }


}