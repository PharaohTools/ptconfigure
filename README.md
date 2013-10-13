Golden Contact Computing - Cleopatra Tool
-------------------


Installation
-----------------
To install cleopatra cli on your machine do the following. If you already have php5 and git installed skip line 1:

  CentOS do this              | Ubuntu do this
  sudo yum install php5 git   | sudo apt-get install php5 git

git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install-silent

or...

git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install (If you want to choose the install dir)

... that's it, now the cleopatra command should be available at the command line for you.


About:
-----------------
Systems Automation in PHP for Linux - think Chef, Puppet, and now Cleopatra. Set up your Dev client, Dev server, Git
Server, Testing Server or Prod Server in minutes - or silently.

This tools is for provisioning software and configurations to your boxes

Can be used to set up a Development Client, Development Server, Testing Server, or Production Server in minutes

You can set up complex provisions to your systems with one or two PHP files.



Usage:
-----------------

So, there are a few simple commands...

First, you can just use

cleopatra

...This will give you a list of the available modules...


Then you can use

cleopatra *ModuleName* help

...This will display the help for that module, and tell you a list of available alias for the module command, and the
available actions too.


You can also use these out of the box groups of packages...

cleopatra InstallPackage dev-client - Install preconfigured software/config for a dev client (Your Workstation)
cleopatra InstallPackage dev-server - Install preconfigured software/config for a dev server (Team Playaround Box)
cleopatra InstallPackage test-server - Install preconfigured software/config for a Build/Testing server
cleopatra InstallPackage git-server - Install preconfigured software/config for a Git SCM server
cleopatra InstallPackage production - Install preconfigured software/config for a Production server (Public Server)