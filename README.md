# Dapperstrano, Pharaoh Tools


## About:


Automated Deployment, Web App Versioning and Infrastructure by Code in PHP. Dapperstrano deploys PHP Applications in a
really simple way, and does it all through code configuration. That's what it's about.

This tool is for provisioning applications and builds to your boxes. You can set up simple or complex application
deployment patterns to your systems with one or two PHP files, or quickly set up cloud friendly deployment patterns.

Dapperstrano is modular. object oriented and extendible, you can pretty easily write your own module if you want
functionality we haven't yet covered. Feel free to submit us pull requests.

This is part of the Pharaoh Tools suite, which covers Configuration Management, Test Automation Management, Automated
Deployment, Build and Release Management and more, all implemented in code, and all in PHP.

Its easy to write modules for any Operating System but we've begun with Ubuntu and adding more as soon as possible.
Currently, all of the Modules work on Ubuntu 12, most on 13, and a few on Centos.

If you've heard of the Ruby tool Capistrano, then you can probably guess why this is called Dapperstrano. It performs a
similar function (app deployment), but does it in PHP (Because PHP is way cooler). This tool helps just as well with
setting up projects locally or on 50 remote servers. It's really cool for cloning / installing / spinning up web
apps easily and quickly - to one or multiple servers using one or two config files. Just as Capistrano is a must for
your Ruby CI setup, Dapperstrano is a must for your PHP CI.


## Installation

The preferred way to install any of the Pharaoh apps (including this) is through cleopatra. If you install cleopatra
on your machine (http://git.pharaoh-tools.com/phpengine/cleopatra), then you can install dapperstrano using the following:

sudo cleopatra dapperstrano install --yes --guess

You can omit the --guess to pick your own installation directory. To install dapperstrano cli on your machine
without cleopatra, do the following. You'll need to already have php5 and git installed.

To install dapperstrano cli on your machine without cleopatra do the following:

sudo apt-get install php5 git

git clone https://git.pharaoh-tools.com/phpengine/dapperstrano && sudo php dapperstrano/install-silent

or...

git clone https://git.pharaoh-tools.com/phpengine/dapperstrano && sudo php dapperstrano/install
(if you want to choose the install location)

... that's it, now the dapperstrano command should be available at the command line for you.


## Available Commands:

Remember - You can get full help on any command by entering dapperstrano command help ie dapperstrano apachecontrol help.
Also Remember - Dapperstrano is very extendable and modular, so everything can be overridden easily and new modules,
commands or features can be added easily too. Below describes only the commands that are currently in Default Modules.

- ApacheControl - Apache Server Control
- ApacheVHostEditor - Apache Virtual Host Functions
- AppSettings - Dapperstrano Application Settings
- Autopilot - Dappestrano Autopilot - User Defined Installations
- Builderfy - Dapperstrano Builderfyer - Create some standard autopilots for your project
- CukeConf - Cucumber Configuration
- DBConfigure - Database Connection Configuration Functions
- DBInstall - Database Installation Management Functions
- Dapperfy - Dapperstrano Dapperfyer - Create some standard autopilots for your project
- EnvironmentConfig - Environment Configuration - Configure Environments for a project
- GitClone - GitClone Source Control Clone Functions
- HostEditor - Host File Management Functions
- Invoke - SSH Invocation Functions
- LighttpdControl - Lighttpd Server Control
- Logging - Logging - Output errors to the logging
- NginxControl - Nginx Server Control
- NginxSBEditor - Nginx Server Block Functions
- ParallelSshChild - Command Execution Functions
- Project - Dapperstrano Project Management Functions
- SVN - SVN Source Control Project Checkout/Download Functions
- SystemDetection - System Detection - Detect the Running Operating System
- Templating - Templating
- Version - Versioning Functions