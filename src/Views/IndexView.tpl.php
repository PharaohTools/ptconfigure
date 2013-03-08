Available Commands:
---------------------------------------

install       - cli
                install a full web project - Checkout, Vhost, Hostfile, Cucumber Configuration, Database and Jenkins
                Job. The installer will ask you for required values
                example: devhelper install cli

              - autopilot
                perform an "unattended" install using the defults in an autopilot file. Great for Remote Builds.
                example: devhelper install autopilot

checkout,     - perform a checkout into configured projects folder
co              example: devhelper co git https://github.com/phpengine/yourmum {optional custom clone dir}

cukeconf,     - conf
cuke            modify the url used for cucumber features testing
                example: devhelper cukeconf cli

              - reset
                reset cuke uri to generic values so devhelper can write them. may need to be run before cuke conf.
                example: devhelper cukeconf reset

database, db  - configure, conf
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

hosteditor,   - add
                add a Host File entry
                example: devhelper hosteditor add

              - rm
                remove a Host File entry
                example: devhelper hosteditor rm

invoke, inv   - shell
                Will use the values stored in the project file for servers
                example: devhelper invoke shell production autopilot-settings-file

              - script
                execute each line of a provided script file on the remote/s
                example: devhelper invoke script autopilot-script-file

project, proj - init
                initialize DH project
                example: devhelper proj init

              - build-install
                copy jenkins project stored in repo to running jenkins so you can run builds
                example: devhelper proj build-install

vhosteditor,  - add
vhc             create a Virtual Host
                example: devhelper vhc add

              - rm
                remove a Virtual Host
                example: devhelper vhc rm