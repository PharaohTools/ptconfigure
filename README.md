Cleopatra, Pharoah Tools
-------------------

About:
-----------------

Configuration Management, Systems Automation and Infrastructure by Code in PHP. Provision your boxes manually or
with an Operating System agnostic method of ensuring environment stability.

This tool is for provisioning software and configurations to your boxes. You can set up complex provisions to your
systems with one or two PHP files, or quickly set up cloud friendly deployment patterns.

Cleopatra is modular. object oriented and extendible, you can pretty easily write your own module that will configure
the way that you want them if you want functionality we haven't yet covered. Feel free to submit us pull requests.

This is part of the Pharoah Tools suite, which covers Configuration Management, Test Automation Management, Automated
Deployment, Build and Release Management and more, all implemented in code, and all in PHP.

Currently, all of the Modules work on Ubuntu 12, most on 13, and a few on Centos. It's reasonably easy for most devs to
write for.


    
Installation
-----------------
On your Mac, Linux, Unix or Windows Machine, you'll need to install Git and PHP5. If you don't have either, google
them they are easy to install. To install cleopatra cli on your machine do the following at the command line.

git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install-silent

or...

git clone https://github.com/phpengine/cleopatra && sudo php cleopatra/install (If you want to choose the install dir)

... that's it, now the cleopatra command should be available at the command line for you.



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

You'll be able to automate any action from any available module into an autopilot file, or run it from the CLI. I'm
working on a web front end, but you can also use JSON output and the PostInput module to use any module from an API.

Go to http://www.pharoah-tools.org.uk for more