# Cleopatra, Pharaoh Tools

## About:

Configuration Management, Systems Automation and Infrastructure by Code in PHP. Provision your boxes manually or
with an Operating System agnostic method of ensuring environment stability.

This tool is for provisioning software and configurations to your boxes. You can set up complex provisions to your
systems with one or two PHP files, or quickly set up cloud friendly deployment patterns.

Cleopatra is modular. object oriented and extendible, you can pretty easily write your own module if you want
functionality we haven't yet covered. Feel free to submit us pull requests.

This is part of the Pharaoh Tools suite, which covers Configuration Management, Test Automation Management, Automated
Deployment, Build and Release Management and more, all implemented in code, and all in PHP.

Its easy to write modules for any Operating System but we've begun with Ubuntu and adding more as soon as possible.
Currently, all of the Modules work on Ubuntu 12, most on 13, and a few on Centos.

    
## Installation

First you'll need to install Git and PHP5. If you don't have either, google them - they're easy to install. To install
cleopatra cli on your On your Mac, Linux or  Unix Machine silently do the following:

git clone https://github.com/PharaohTools/cleopatra.git && sudo php cleopatra/install-silent

or on Windows ...

git clone https://github.com/PharaohTools/cleopatra.git && cleopatra\cleo-winstall.bat && php cleopatra\install-silent

... that's it, now the cleopatra command should be available at the command line for you.


## Usage:

So, there are a few simple commands...

First, you can just use

cleopatra

...This will give you a list of the available modules...


Then you can use

cleopatra *ModuleName* help

...This will display the help for that module, and tell you a list of available alias for the module command, and the
available actions too.

You'll be able to automate any action from any available module into an autopilot file, or run it from the CLI. I'm
working on a web front end, but you can also use JSON output and the PostInput module to use any module from an API.


## Or a quick example

These 5 commands will fire you up 5 boxes on Digital Ocean, and configure the systems as a Bastion Server, a Git Server,
a Jenkins Build Server, a Standalone PHP/Mysql Staging Server, and a Standalone PHP/Mysql Production Server. You'll need
to set up your Digital Ocean account first.

 # create a directory, or use a current web project as your new Pharaoh project
 mkdir /var/www/my-test-project && cd /var/www/my-test-project

 # boxify
 cleopatra autopilot execute /opt/cleopatra/cleopatra/src/Modules/Boxify/Autopilots/boxify-add-tiny.php
 (will ask for api key and client id the first time)

 # cleofy - create some standard templates for
 cleopatra cleofy standard --yes --guess

 # The "tiny" set of Server Configuration, comes with a script to kick off the invokers for all the environments
 cp /opt/cleopatra/cleopatra/src/Modules/Boxify/Scripts/cm-all-tiny.sh .

 # Run it all
 sudo sh cm-all-tiny.sh

Use invoke cli or env-config list to see your boxes

Go to http://www.pharaohtools.com for more


## Available Commands:

- DummyLinuxModule - Dummy Linux Module
- GameBlocks - Game Blocks Server Management Functions
- AWSCloudFormation - The AWS CloudFormation CLI Tools
- AWSCloudWatch - The AWS CloudWatch CLI Tools
- AWSEC2 - AWS EC2 Server Management Functions
- ApacheConf - Apache Conf - Install a Apache Configuration
- ApacheModules - Apache Modules - Commonly used modules for Apache
- ApacheReverseProxyModules - Apache Reverse Proxy Modules - Reverse Proxy/Load Balancer Modules for Apache
- ApacheServer - Apache Server - Install or remove the Apache Server
- Apt - Add, Remove or Modify Apts
- Autopilot - Cleopatra Autopilot - User Defined Installations
- Behat - Behat - The PHP BDD Testing Suite
- Boxify - Boxify Wrapper - Create Cloud Instances
- Citadel - Citadel Server - Install or remove the Citadel Server
- Cleofy - Cleopatra Cleofyer - Creates default autopilots for your project
- Cleopatra - Cleopatra - Upgrade or Re-install Cleopatra
- Copy - Copy Functionality
- Dapperstrano - Dapperstrano - The PHP Automated Website Deployment tool
- DeveloperTools - Developer Tools - IDE's and other tools for Developers
- DigitalOcean - Digital Ocean Server Management Functions
- Encryption - Encryption or Decryption of files
- EnvironmentConfig - Environment Configuration - Configure Environments for a project
- File - Add, Remove or Modify Files
- Firefox - Firefox - Install or remove Firefox
- Firefox14 - Firefox 14 - A version of Firefox highly tested with Selenium Server
- Firefox17 - Firefox 17 - A version of Firefox highly tested with Selenium Server
- Firewall - Add, Remove or Modify Firewalls
- GIMP - GIMP - The Image Editor
- Gem - Ruby Gems Package Manager
- Generator - Dapperstrano Autopilot Generator - Generate Autopilot files interactively
- GitBucket - Git Bucket - The Git SCM Management Web Application
- GitKeySafe - Git Key-Safe - Install a script for git to allow specifying ssh keys during commands
- GitLab - Git Lab - The Git SCM Management Web Application
- GitTools - Git Tools - Tools for working with Git SCM
- HAProxy - HA Proxy Server - Install or remove the HA Proxy Server
- Hostname - View or Modify Hostname
- InstallPackage - Cleopatra Predefined Installers
- IntelliJ - IntelliJ - A great IDE from JetBrains
- Invoke - SSH Invocation Functions
- JRush - JRush - The Joomla command line utility from Golden Contact
- Java - Java JDK 1.7
- Jenkins - Jenkins - The Java Build Server
- JenkinsPlugins - Jenkins PHP Plugins - Common Plugins for Jenkins PHP Builds
- JenkinsSudoNoPass - Configure Passwordless Sudo for your Jenkins user
- LigHTTPDServer - LigHTTPD Server - Install or remove the LigHTTPD Server
- Logging - Logging - Output errors to the logging
- MediaTools - Media Tools - Tools to help view and manage Media files
- ModuleManager - Manage the modules used in Cleopatra
- MysqlAdmins - Mysql Admins - Install administrative users for Mysql
- MysqlServer - Mysql Server - The Mysql RDBMS Server
- MysqlServerGalera - Mysql Server Galera - The Galera Clustering compatible version of Mysql RDBMS Server
- MysqlTools - Mysql Tools - For administering and developing with Mysql
- NagiosServer - Nagios Server - Install or remove the Nagios Server
- NetworkTools - Network Tools - Tools for working with Networks
- NginxServer - Nginx Server - Install or remove the Nginx Server
- NodeJS - Node JS - The Server Side Javascript Engine
- PHPAPC - PHP APC - Commonly used PHP APC
- PHPCS - PHP Code Sniffer - The static code analysis tool
- PHPConf - PHP Conf - Install a PHP Configuration
- PHPMD - PHP Mess Detector - The static analysis tool
- PHPModules - PHP Modules - Commonly used PHP Modules
- PHPStorm - PHPStorm - A great IDE from JetBrains
- PHPUnit - PHP Unit - The PHP Implementation of the XUnit Unit Testing standard
- PackageManager - Native Package Manager Wrapper - Install OS neutral packages
- PaloAlto - Palo Alto Firewall Management Functions
- PapyrusEditor - Papyrus Editor Web Interface
- Parallax - Parallax - Execute commands in parallel
- Pear - Pear Package Manager
- Phake - Phake - The PHP task creation tool (Make/Rake)
- PharaohTools - Pharaoh Tools - Gotta Install them all
- Phlagrant - Phlagrant - The Virtual Machine management solution for PHP
- Phrankinsense - Phrankinsense - The Pharaoh Tools Project Management Solution
- Port - Test a Port to see if its responding
- PostInput - HTTP Post/Get Input Interface
- PostgresServer - Postgres Server - The Postgres RDBMS Server
- Python - Python - The programming language
- Ra - Ra - The Pharaoh Tools Build Server
- RubyBDD - Ruby BDD Suite - Install Common Gems for Cucumber, Calabash, Capybara and Saucelabs
- RubyRVM - Ruby RVM - The Ruby version manager
- RubySystem - Ruby RVM System wide - The Ruby version manager system wide version
- RunCommand - Execute a Command
- SFTP - SFTP Functionality
- SVN - SVN - The Source Control Manager
- SeleniumServer - The Selenium Web Browser controlling server
- Service - Start, Stop or Restart a Service
- SshEncrypt - Mysql Admins - Install administrative users for Mysql
- SshHarden - Apply security functions to the SSH accounts/setup of the machine
- SshKeyInstall - Install SSH Public Keys to a user account
- SshKeygen - SSH Keygen - Generate SSH Kay Pairs
- StandardTools - Standard Tools for any Installation
- SudoNoPass - Configure Passwordless Sudo for any User
- SystemDetection - System Detection - Detect the Running Operating System
- Teamcity - Teamcity - The Jetbrains Build Server
- Templating - Install files with placeholders replaced at runtime
- Testingkamen - Upgrade or Re-install Testingkamen
- ThoughtWorksGo - The Continuous Delivery server from ThoughtWorks
- UbuntuCompiler - For Compiling Linux Programs
- User - Add, Remove or Modify Users
- VSphere - VMWare VSphere - Server Management Functions
- WireframeSketcher - Wireframe Sketcher - the Wireframing application
- Yum - Add, Remove or Modify Yum Packages