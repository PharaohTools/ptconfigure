![alt text](http://www.pharaohtools.com/images/logo-pharaoh.png "Pharaoh Tools Automated Application Deployment")

# PTDeploy, Pharaoh Tools


## About:


Automated Deployment, Web App Versioning and Infrastructure by Code in PHP. PTDeploy deploys PHP Applications in a
really simple way, and does it all through code configuration. That's what it's about.

This tool is for provisioning applications and builds to your boxes. You can set up simple or complex application
deployment patterns to your systems with one or two PHP files, or quickly set up cloud friendly deployment patterns.

PTDeploy is modular. object oriented and extendible, you can pretty easily write your own module if you want
functionality we haven't yet covered. Feel free to submit us pull requests.

This is part of the Pharaoh Tools suite, which covers Configuration Management, Test Automation Management, Automated
Deployment, Build and Release Management and more, all implemented in code, and all in PHP.

Its easy to write modules for any Operating System but we've begun with Ubuntu and adding more as soon as possible.
Currently, all of the Modules work on Ubuntu 12, most on 13, and a few on Centos.

Pharaoh Deploy is a little like the Ruby tool Capistrano. It performs a
similar function (app deployment), but does it in PHP (Because PHP is way cooler). This tool helps just as well with
setting up projects locally or on 50 remote servers. It's really cool for cloning / installing / spinning up web
apps easily and quickly - to one or multiple servers using one or two config files. Just as Capistrano is a must for
your Ruby CI setup, PTDeploy is a must for your PHP CI.


## Installation

The preferred way to install any of the Pharaoh apps (including this) is through ptconfigure. If you install ptconfigure
on your machine (http://git.pharaohtools.com/phpengine/ptconfigure), then you can install deploy using the following:

sudo ptconfigure deploy install --yes --guess

You can omit the --guess to pick your own installation directory. To install ptdeploy cli on your machine
without ptconfigure, do the following. You'll need to already have php5 and git installed.

To install ptdeploy cli on your machine without ptconfigure do the following:

sudo apt-get install php5 git

git clone https://git.pharaohtools.com/phpengine/ptdeploy && sudo php ptdeploy/install-silent

or...

git clone https://git.pharaohtools.com/phpengine/ptdeploy && sudo php ptdeploy/install
(if you want to choose the install location)

... that's it, now the ptdeploy command should be available at the command line for you.


## Available Commands:

Remember - You can get full help on any command by entering ptdeploy command help ie ptdeploy apachecontrol help.
Also Remember - PTDeploy is very extendable and modular, so everything can be overridden easily and new modules,
commands or features can be added easily too. Below describes only the commands that are currently in Default Modules.

- ApacheControl - Apache Server Control
- ApacheVHostEditor - Apache Virtual Host Functions
- AppSettings - PTDeploy Application Settings
- Autopilot - Dappestrano Autopilot - User Defined Installations
- Builderfy - PTDeploy Builderfyer - Create some standard autopilots for your project
- CukeConf - Cucumber Configuration
- DBConfigure - Database Connection Configuration Functions
- DBInstall - Database Installation Management Functions
- Dapperfy - PTDeploy Dapperfyer - Create some standard autopilots for your project
- EnvironmentConfig - Environment Configuration - Configure Environments for a project
- GitClone - GitClone Source Control Clone Functions
- HostEditor - Host File Management Functions
- Invoke - SSH Invocation Functions
- LighttpdControl - Lighttpd Server Control
- Logging - Logging - Output errors to the logging
- NginxControl - Nginx Server Control
- NginxSBEditor - Nginx Server Block Functions
- ParallelSshChild - Command Execution Functions
- Project - PTDeploy Project Management Functions
- SVN - SVN Source Control Project Checkout/Download Functions
- SystemDetection - System Detection - Detect the Running Operating System
- Templating - Templating
- Version - Versioning Functions