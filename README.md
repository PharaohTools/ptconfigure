Golden Contact Computing - Dapperstrano
-------------------

About:
-----------------

Dapperstrano deploys PHP Applications in a really simple way, and does it all through code configuration. That's what
it's about.

If you've heard of the Ruby tool Capistrano, then you can probably guess why this is called Dapperstrano. It performs a
similar function (app deployment), but does it in PHP (Because PHP is way cooler). This tool helps just as well with
setting up projects locally or on 50 remote servers. It's really cool for cloning / installing / spinning up web
apps easily and quickly - to one or multiple servers using one or two config files. Just as Capistrano is a must for
your Ruby CI setup, Dapperstrano is a must for your PHP CI. You can install your web app in one line like:

dapperstrano autopilot execute *autopilot-file*



Installation
-----------------

To install dapperstrano cli on your machine do the following. If you already have php5 and git installed skip line 1:

sudo apt-get install php5 git

git clone https://github.com/phpengine/dapperstrano && sudo php dapperstrano/install-silent

or...

git clone https://github.com/phpengine/dapperstrano && sudo php dapperstrano/install
(if you want to choose the install location)

... that's it, now the dapperstrano command should be available at the command line for you.



Available Commands:
---------------------------------------

Remember - You can get full help on any command by entering dapperstrano command help ie dapperstrano apachecontrol help.
Also Remember - Dapperstrano is very extendable and modular, so everything can be overridden easily and new modules,
commands or features can be added easily too. Below describes only the commands that are currently in Default Modules.

ApacheControl - Apache Server Control
AppSettings - Dapperstrano Application Settings
Autopilot - Cleopatra Autopilot - User Defined Installs
CukeConf - Cucumber Configuration
Dapperfy - Dapperstrano Dapperfyer - Create some standard autopilots for your project
Database - Database Management Functions
DigitalOcean - Digital Ocean Server Management Functions
EnvironmentConfig - Environment Configuration - Configure Environments for a project
Generator - Dapperstrano Autopilot Generator
Git - Git Source Control Project Checkout/Download Functions
HostEditor - Host File Management Functions
Invoke - SSH Invocation Functions
LighttpdControl - Lighttpd Server Control
NginxControl - Nginx Server Control
NginxSBEditor - Nginx Server Block Functions
ParallelSshChild - Command Execution Functions
Project - Dapperstrano Project Management Functions
VHostEditor - Apache Virtual Host Functions
Version - Versioning Functions
