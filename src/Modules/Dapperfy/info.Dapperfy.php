<?php

Namespace Info;

class DapperfyInfo extends Base {

    public $hidden = false;

    public $name = "Dapperstrano Dapperfyer - Create some standard autopilots for your project";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Dapperfy" =>  array_merge(parent::routesAvailable(), array("create", "standard") ) );
    }

    public function routeAliases() {
      return array("dapperfy"=>"Dapperfy");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of a default Module Core and provides you with a method by which you can
  create a standard set of Autopilot files for your project from the command line.
  You can configure default application settings, ie: mysql admin user, host, pass


  Dapperfy, dapperfy

        - list
        List all of the autopilot files in your build/config/dapperstrano/autopilots
        example: dapperstrano dapperfy list

        - create
        Create a set of autopilots to manage
        example: dapperstrano dapperfy create

        The start of the command will be dapperstrano autopilot execute :

        cap deploy               # Deploys your project.
        cap deploy:check         # Test deployment dependencies.
        cap deploy:cleanup       # Clean up old releases.
        cap deploy:cold          # Deploys and starts a 'cold' application.
        cap deploy:migrations    # Deploy and run pending migrations.
        cap deploy:pending       # Displays the commits since your last deploy.
        cap deploy:pending:diff  # Displays the `diff' since your last deploy.
        cap deploy:rollback      # Rolls back to a previous version and restarts.
        cap deploy:rollback:code # Rolls back to the previously deployed version.
        cap deploy:start         # Blank task exists as a hook into which to install ...
        cap deploy:stop          # Blank task exists as a hook into which to install ...
        cap deploy:symlink       # Updates the symlink to the most recently deployed ...
        cap deploy:update        # Copies your project and updates the symlink.
        cap deploy:update_code   # Copies your project to the remote servers.
        cap deploy:upload        # Copy files to the currently deployed version.
        cap deploy:web:disable   # Present a maintenance page to visitors.
        cap deploy:web:enable    # Makes the application web-accessible again.
        cap invoke               # Invoke a single command on the remote servers.
        cap shell                # Begin an interactive Capistrano session.

HELPDATA;
      return $help ;
    }

}