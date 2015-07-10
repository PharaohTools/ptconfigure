# PTConfigure, Pharaoh Tools

## About:

Configuration Management, Systems Automation and Infrastructure by Code in PHP. Provision your boxes manually or
with an Operating System agnostic method of ensuring environment stability.

This tool is for provisioning software and configurations to your boxes. You can set up complex provisions to your
systems with one or two PHP files, or quickly set up cloud friendly deployment patterns.

PTConfigure is modular. object oriented and extendible, you can pretty easily write your own module if you want
functionality we haven't yet covered. Feel free to submit us pull requests.

This is part of the Pharaoh Tools suite, which covers Configuration Management, Test Automation Management, Automated
Deployment, Build and Release Management and more, all implemented in code, and all in PHP.

Its easy to write modules for any Operating System but we've begun with Ubuntu and adding more as soon as possible.
Currently, all of the Modules work on Ubuntu 12+, most on Centos and Windows.

    
## Installation

First you'll need to install Git and PHP5. If you don't have either, google them - they're easy to install. To install
ptconfigure cli on your On your Mac, Linux or  Unix Machine silently do the following:

git clone https://github.com/PharaohTools/ptconfigure.git && sudo php ptconfigure/install-silent

or on Windows, open a terminal with the "Run as Administrator" option...

git clone https://github.com/PharaohTools/ptconfigure.git && php ptconfigure\install-silent

... that's it, now the ptconfigure command should be available at the command line for you.


## Usage:

So, there are a few simple commands...

First, you can just use

ptconfigure

...This will give you a list of the available modules...

Then you can use

ptconfigure *ModuleName* help

...This will display the help for that module, and tell you a list of available alias for the module command, and the
available actions too.

You'll be able to automate any action from any available module into an autopilot file, or run it from the CLI. I'm
working on a web front end, but you can also use JSON output and the PostInput module to use any module from an API.


## Or some examples

The following URL contains a bunch of tutorials

http://www.pharaohtools.com/tutorials

Go to http://www.pharaohtools.com for more


## Available Commands:

 - DummyLinuxModule - Dummy Linux Module
 - AWSCloudFormation - The AWS CloudFormation CLI Tools
 - AWSCloudWatch - The AWS CloudWatch CLI Tools
 - AWSEC2 - AWS EC2 Server Management Functions
 - ApacheConf - Apache Conf - Install a Apache Configuration
 - ApacheModules - Apache Modules - Commonly used modules for Apache
 - ApacheReverseProxyModules - Apache Reverse Proxy Modules - Reverse Proxy/Load Balancer Modules for Apache
 - ApacheServer - Apache Server - Install or remove the Apache Server
 - Apt - Add, Remove or Modify Apts
 - Autopilot - PTConfigure Autopilot - User Defined Installations
 - Behat - Behat - The PHP BDD Testing Suite
 - Boxify - Boxify Wrapper - Create Cloud Instances
 - Chgrp - Chgrp Functionality
 - Chmod - Chmod Functionality
 - Chown - Chown Functionality
 - Chrome - Chrome - Install or remove Chrome
 - ChromeDriver - The Chrome Browser remote controlling server
 - Citadel - Citadel Server - Install or remove the Citadel Server
 - Cleofy - PTConfigure Cleofyer - Creates default autopilots for your project
 - PTConfigure - PTConfigure - Upgrade or Re-install PTConfigure
 - Composer - Composer - Upgrade or Re-install Composer
 - Copy - Copy Functionality
 - DNSify - DNSify Wrapper - Ensure the existence or removal of DNS records
 - PTDeploy - PTDeploy - The PHP Automated Website Deployment tool
 - DeveloperTools - Developer Tools - IDE's and other tools for Developers
 - DigitalOcean - Digital Ocean Server Management Functions
 - DigitalOceanV2 - Digital Ocean Server Management Functions - API Version 2
 - Encryption - Encryption or Decryption of files
 - EnvironmentConfig - Environment Configuration - Configure Environments for a project
 - File - Functions to Modify Files
 - Firefox - Firefox - Install or remove Firefox
 - Firefox14 - Firefox 14 - A version of Firefox highly tested with Selenium Server
 - Firefox17 - Firefox 17 - A version of Firefox highly tested with Selenium Server
 - Firefox24 - Firefox 24 - A version of Firefox highly tested with Selenium Server
 - Firefox33 - Firefox 33 - A version of Firefox highly tested with Selenium Server
 - Firewall - Add, Remove or Modify Firewalls
 - GIMP - GIMP - The Image Editor
 - Gem - Ruby Gems Package Manager
 - Generator - PTDeploy Autopilot Generator - Generate Autopilot files interactively
 - GitBucket - Git Bucket - The Git SCM Management Web Application
 - GitKeySafe - Git Key-Safe - Install a script for git to allow specifying ssh keys during commands
 - GitLab - Git Lab - The Git SCM Management Web Application
 - GitTools - Git Tools - Tools for working with Git SCM
 - HAProxy - HA Proxy Server - Install or remove the HA Proxy Server
 - HHVM - HHVM - The PHP Virtual Machine runtime from Facebook
 - Hostname - View or Modify Hostname
 - InstallPackage - PTConfigure Predefined Installers
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
 - Mkdir - Mkdir Functionality
 - ModuleManager - Manage the modules used in PTConfigure
 - MongoDB - MongoDB Server - The MongoDB Datastore Server
 - MysqlAdmins - Mysql Admins - Install administrative users for Mysql
 - MysqlServer - Mysql Server - The Mysql RDBMS Server
 - MysqlServerGalera - Mysql Server Galera - The Galera Clustering compatible version of Mysql RDBMS Server
 - MysqlTools - Mysql Tools - For administering and developing with Mysql
 - NagiosServer - Nagios Server - Install or remove the Nagios Server
 - NetworkTools - Network Tools - Tools for working with Networks
 - NginxServer - Nginx Server - Install or remove the Nginx Server
 - NodeJS - Node JS - The Server Side Javascript Engine
 - PECL - Add, Remove or Modify PECLs
 - PHPAPC - PHP APC - Commonly used PHP APC
 - PHPCI - PHPCI - The PHP Build Server
 - PHPCS - PHP Code Sniffer - The static code analysis tool
 - PHPConf - PHP Conf - Install a PHP Configuration
 - PHPMD - PHP Mess Detector - The static analysis tool
 - PHPModules - PHP Modules - Commonly used PHP Modules
 - PHPSSH - PHP SSH - PHP SSH Extension
 - PHPStorm - PHPStorm - A great IDE from JetBrains
 - PHPUnit - PHP Unit - The PHP Implementation of the XUnit Unit Testing standard
 - PackageManager - Native Package Manager Wrapper - Install OS neutral packages
 - PapyrusEditor - Papyrus Editor Web Interface
 - Parallax - Parallax - Execute commands in parallel
 - Pear - Pear Package Manager
 - Phake - Phake - The PHP task creation tool (Make/Rake)
 - PharaohTools - Pharaoh Tools - Gotta Install them all
 - PTVirtualize - PTVirtualize - The Virtual Machine management solution for PHP
 - Phrankinsense - Phrankinsense - The Pharaoh Tools Project Management Solution
 - Ping - Test a Ping to see if its responding
 - Port - Test a Port to see if its responding
 - PostInput - HTTP Post/Get Input Interface
 - PostgresServer - Postgres Server - The Postgres RDBMS Server
 - Process - Process Functionality
 - Python - Python - The programming language
 - Ra - Ra - The Pharaoh Tools Build Server
 - Rackspace - Rackspace/Opencloud Cloud Management Functions
 - RubyBDD - Ruby BDD Suite - Install Common Gems for Cucumber, Calabash, Capybara and Saucelabs
 - RubyRVM - Ruby RVM - The Ruby version manager
 - RubySystem - Ruby RVM System wide - The Ruby version manager system wide version
 - RunCommand - Execute a Command
 - SFTP - SFTP Functionality
 - SVN - SVN - The Source Control Manager
 - SeleniumServer - The Selenium Web Browser controlling server
 - Service - Start, Stop or Restart a Service
 - SshEncrypt - Install/encrypt private SSH keys
 - SshHarden - Apply security functions to the SSH accounts/setup of the machine
 - SshKeyInstall - Install SSH Public Keys to a user account
 - SshKeyStore - Install SSH Public Keys to a user account
 - SshKeygen - SSH Keygen - Generate SSH Kay Pairs
 - StandardTools - Standard Tools for any Installation
 - SudoNoPass - Configure Passwordless Sudo for any User
 - SystemDetection - System Detection - Detect the Running Operating System
 - Teamcity - Teamcity - The Jetbrains Build Server
 - Templating - Install files with placeholders or lines replaced at runtime
 - PTTest - Upgrade or Re-install PTTest
 - ThoughtWorksGo - The Continuous Delivery server from ThoughtWorks
 - UbuntuCompiler - For Compiling Linux Programs
 - User - Add, Remove or Modify Users
 - VNC - VNC - The Display Manager Solution
 - VNCPasswd - VNCPasswd - The Display Manager Solution
 - VSphere - VMWare VSphere - Server Management Functions
 - Varnish - The HTTP Cache
 - Virtualbox - Virtualbox - The local Virtual Machine Solution
 - WinExe - Add, Remove or Modify WinExes
 - WireframeSketcher - Wireframe Sketcher - the Wireframing application
 - Xvfb - Xvfb - The Display Manager Solution
 - Yum - Add, Remove or Modify Yum Packages

---------------------------------------
Visit www.pharaohtools.com for more
******************************