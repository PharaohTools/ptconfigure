Golden Contact Computing - Cleopatra Tool
-------------------


Installation
-----------------
To install cleopatra cli on your machine do the following. If you already have php5 and git installed skip line 1:

sudo apt-get install php5 git

git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install-silent

or...

git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install (If you want to choose the install dir)

... that's it, now the cleopatra command should be available at the command line for you.


About:
-----------------
Systems Automation in PHP for Linux - think Chef, Puppet, and now Cleopatra. Set up your Dev client, Dev server, Git
Server, Testing Server or Prod Server in minutes - or silently.

This tool helps with setting up boxes. Its intended to get any box in your standard main environments to be
up and running quickly. It's not meant to be an exhaustive list of installs for everything you'll ever need to
install (obviously)

Can be used to set up a Development Client, Development Server, Testing Server, or Production Server in minutes

... Staging/UAT is not missing from the list because in "Software/Configuration on this box" terms it should be the
same as Production.

Furthermore, especially for Production, this is intended to be a quick solution to get you up and running and I
do not and would never recommend going into Production without checking things for yourself...
Think of it like this - We can give you alcohol to help your flow, but can't hold it for you when you need to go.

Cleopatra is extremely extendable, you can pretty easily write your own module that will configure your prod servers
the way that you want them - and then use that as you will get the same automation benefits along with the security
and infrastructure benefits of doing it correctly for your own setup


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