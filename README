Golden Contact Computing - Dapperstrano Tool
-------------------

About:
-----------------
This tool helps with setting up projects. It's really cool for cloning/installing/spinning up webs apps easily and
quickly.

Very cool for CI, after your CI tool performs the project checkout to run tests, you can install your webb app in one
line like:

dapperstrano install autopilot *autopilot-file*


Installation
-----------------

To install dapperstrano cli on your machine do the following. If you already have php5 and git installed skip line 1:

sudo apt-get install php5 git

git clone https://github.com/phpengine/dapperstrano && sudo php dapperstrano/install-silent

or...

git clone https://github.com/phpengine/dapperstrano && sudo php dapperstrano/install
(if you want to choose the install location)

... that's it, now the dapperstrano command should be available at the command line for you.

-------------------------------------------------------------

Available Commands:
---------------------------------------

install

              - cli
                install a full web project - Checkout, VHost, Hostfile, Cucumber Configuration, Database Install and
                Settings Config, and Jenkins Job. The installer will ask you for required values
                example: dapperstrano install cli

              - autopilot
                perform an "unattended" install using the defaults in an autopilot file. Great for Remote Builds.
                This is the implementation of the Cap Deploy command
                example: dapperstrano install autopilot

appsettings   - configure default application settings, ie: mysql admin user, host, pass

              - set
                Set a configuration value
                example: dapperstrano appsettings set

              - get
                Get the value of a setting you have configured
                example: dapperstrano appsettings get

              - delete
                Delete a setting you have configured
                example: dapperstrano appsettings delete

              - list
                Display a list of all default available settings
                example: dapperstrano appsettings list

checkout, co

              - perform a checkout into configured projects folder. If you dont want to specify target dir but do want
                to specify a branch, then enter the text "none" as that parameter.
                example: dapperstrano co git https://github.com/phpengine/yourmum {optional target dir} {optional branch}
                example: dapperstrano co git https://github.com/phpengine/yourmum none {optional branch}

cukeconf, cuke

              - conf
                modify the url used for cucumber features testing
                example: dapperstrano cukeconf cli

              - reset
                reset cuke uri to generic values so dapperstrano can write them. may need to be run before cuke conf.
                example: dapperstrano cukeconf reset

database, db

              - configure, conf
                set up db user & pw for a project, use admins to create new resources as needed.
                example: dapperstrano db conf drupal

              - reset
                reset current db to generic values so dapperstrano can write them. may need to be run before db conf.
                example: dapperstrano db reset drupal

              - install
                install the database for a project. run conf first to set up users unless you already have them.
                example: dapperstrano db install

              - drop
                drop the database for a project.
                example: dapperstrano db drop

hosteditor

              - add
                add a Host File entry
                example: dapperstrano hosteditor add

              - rm
                remove a Host File entry
                example: dapperstrano hosteditor rm

invoke, inv   

              - cli
                Will ask you for details for servers, then open a shell for you to execute on multiple servers
                example: dapperstrano invoke shell

              - script
                Will ask you for details for servers, then execute each line of a provided script file on the remote/s
                example: dapperstrano invoke script script-file

              - autopilot
                execute each line of a script file, multiple script files, or php variable data on one or more remotes
                example: dapperstrano invoke autopilot autopilot-file

project, proj

              - container
                make a container folder for revisions (like /var/www/applications/*APP NAME*)
                example: dapperstrano proj container

              - init @todo should question for dh proj structure
                initialize an existing directory as a DH project
                example: dapperstrano proj init

              - new @todo all of this command
                Create a new Project, using dapperstrano default directory structure, including
                creating the Git Repo and Master, Staging and Production branches. Also install
                the appropriate CD/CI/Test builds on the Main Jenkins server, so we can create
                a new CD project from one command.
                example: dapperstrano proj init

              - build-install @todo should question jenkins username/email
                copy jenkins project stored in repo to running jenkins so you can run builds
                example: dapperstrano proj build-install

              - build-save @todo all of this command
                copy jenkins project from running jenkins to repo, with Generic Values that can
                make the Repo version free of personalisation and installable by dapperstrano.
                example: dapperstrano proj build-install

version

              - cli
                Will change back the *current* symlink to whichever available version you pick
                example: dapperstrano version cli

              - latest
                Will change back the *current* symlink to the latest created version
                example: dapperstrano version latest

              - rollback
                Will change back the *current* symlink to the latest created version but one
                example: dapperstrano version rollback


vhosteditor, vhc

              - add
                create a Virtual Host
                example: dapperstrano vhc add

              - rm
                remove a Virtual Host
                example: dapperstrano vhc rm

              - list
                List current Virtual Hosts
                example: dapperstrano vhc list