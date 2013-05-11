Golden Contact Computing - Devhelper Tool
-------------------

About:
-----------------
This tool helps with setting up projects. It's really cool for cloning/installing/spinning up webs apps easily and
quickly.

Very cool for CI, after your CI tool performs the project checkout to run tests, you can install your webb app in one
line like:

devhelper install autopilot *autopilot-file*


Installation
-----------------

To install devhelper cli on your machine do the following. If you already have php5 and git installed skip line 1:

line 1: apt-get php5 git
line 2: git clone https://github.com/phpengine/devhelper && sudo devhelper/install

... that's it, now the devhelper command should be available at the command line for you.

-------------------------------------------------------------

Available Commands:
---------------------------------------

install

        - cli
        install a full web project - Checkout, VHost, Hostfile, Cucumber Configuration, Database Install and
        Settings Config, and Jenkins Job. The installer will ask you for required values
        example: devhelper install cli

        - autopilot
        perform an "unattended" install using the defaults in an autopilot file. Great for Remote Builds.
        example: devhelper install autopilot

appsettings

        - configure default application settings, ie: mysql admin user, host, pass

        - set
        Set a configuration value
        example: devhelper appsettings set

        - get
        Get the value of a setting you have configured
        example: devhelper appsettings get

        - delete
        Delete a setting you have configured
        example: devhelper appsettings delete

        - list
        Display a list of all default available settings
        example: devhelper appsettings list


checkout, co

        - perform a checkout into configured projects folder. If you don't want to specify target dir but do want
        to specify a branch, then enter the text "none" as that parameter.
        example: devhelper co git https://github.com/phpengine/yourmum {optional target dir} {optional branch}
        example: devhelper co git https://github.com/phpengine/yourmum none {optional branch}

cukeconf, cuke

        - conf
        modify the url used for cucumber features testing
        example: devhelper cukeconf cli

        - reset
        reset cuke uri to generic values so devhelper can write them. may need to be run before cuke conf.
        example: devhelper cukeconf reset

database, db

        - configure, conf
        set up db user & pw for a project, use admins to create new resources as needed.
        example: devhelper db conf drupal

        - reset
        reset current db to generic values so devhelper can write them. may need to be run before db conf.
        example: devhelper db reset drupal

        - install
        install the database for a project. run conf first to set up users unless you already have them.
        example: devhelper db install

        - drop
        drop the database for a project.
        example: devhelper db drop

hosteditor

        - add
        add a Host File entry
        example: devhelper hosteditor add

        - rm
        remove a Host File entry
        example: devhelper hosteditor rm

invoke, inv

        - cli
        Will ask you for details for servers, then open a shell for you to execute on multiple servers
        example: devhelper invoke shell

        - script
        Will ask you for details for servers, then execute each line of a provided script file on the remote/s
        example: devhelper invoke script script-file

        - autopilot
        execute each line of a script file, multiple script files, or php variable data on one or more remotes
        example: devhelper invoke autopilot autopilot-file

project, proj

        - container
        make a container folder for revisions (like /var/www/applications/*APP NAME*)
        example: devhelper proj container

        - init
        initialize DH project
        example: devhelper proj init

        - build-install
        copy jenkins project stored in repo to running jenkins so you can run builds
        example: devhelper proj build-install

version

        - cli
        Will change back the *current* symlink to whichever available version you pick
        example: devhelper version cli

        - latest
        Will change back the *current* symlink to the latest created version
        example: devhelper version latest

        - rollback
        Will change back the *current* symlink to the latest created version but one
        example: devhelper version rollback


vhosteditor, vhc

        - add
        create a Virtual Host
        example: devhelper vhc add

        - rm
        remove a Virtual Host
        example: devhelper vhc rm

        - list
        List current Virtual Hosts
        example: devhelper vhc list