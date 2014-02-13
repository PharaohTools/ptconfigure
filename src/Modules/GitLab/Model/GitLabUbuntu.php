<?php

Namespace Model;

class GitLabUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "GitLab";
    $this->installCommands = array(
      "apt-get install -y build-essential zlib1g-dev libyaml-dev ".
        "libssl-dev libgdbm-dev libreadline-dev libncurses5-dev libffi-dev" .
        "curl git-core openssh-server redis-server checkinstall libxml2-dev" .
        "libxslt-dev libcurl4-openssl-dev libicu-dev",
      "apt-get install -y python python-docutils",

      # Fork Cleopatra To
      "apt-get install -y git git-core gitk git-cola",

      # Fork Cleopatra To install git tools
      "cleopatra gittools install --yes=true",


      // make a git user
      "adduser --disabled-login --gecos 'GitLab' git",

      # Ruby
      "sudo apt-get remove -y ruby1.8",
      "mkdir /tmp/ruby && cd /tmp/ruby",
      "curl --progress ftp://ftp.ruby-lang.org/pub/ruby/2.0/ruby-2.0.0-p247.tar.gz | tar xz",
      "cd ruby-2.0.0-p247",
      "./configure",
      "make",
      "make install",
      "gem install bundler --no-ri --no-rdoc",


      // set up gitlab shell
      # Go to home directory
      "cd /home/git",
      # Clone gitlab shell
      "su git -c'git clone https://github.com/gitlabhq/gitlab-shell.git' ",
      "cd gitlab-shell",
      # switch to right version
      "su git -c'git checkout v1.7.1'",
      "su git -c'cp config.yml.example config.yml'",
      # Edit config and replace gitlab_url
      # with something like 'http://domain.com/'
      "su git -c'editor config.yml'",
      # Do setup
      "sudo -u git -H ./bin/install",

      "cd /home/git",



      # Clone GitLab repository
      "sudo -u git -H git clone https://github.com/gitlabhq/gitlabhq.git gitlab",

      # Go to gitlab dir
      "cd /home/git/gitlab",

      # Checkout to stable release
      "sudo -u git -H git checkout 6-1-stable",

      # We'll install GitLab into home directory of the user "git"
      "cd /home/git",

      # Clone the Source
      # Clone GitLab repository
            "su -c 'git clone https://github.com/gitlabhq/gitlabhq.git gitlab'",

      # Go to gitlab dir
            "cd /home/git/gitlab",

      # Checkout to stable release
            "su -c 'git checkout 6-1-stable'",

      # Configure it
            "su -c'cd /home/git/gitlab'",

      # Copy the example GitLab config
            "su -c'git -H cp config/gitlab.yml.example config/gitlab.yml'",

      # Make sure to change "localhost" to the fully-qualified domain name of your
      # host serving GitLab where necessary
            "su -c'git -H editor config/gitlab.yml'",

      # Make sure GitLab can write to the log/ and tmp/ directories
      "chown -R git log/",
      "chown -R git tmp/",
      "chmod -R u+rwX  log/",
      "chmod -R u+rwX  tmp/",

      # Create directory for satellites
      "su -c'git -H mkdir /home/git/gitlab-satellites'",

      # Create directories for sockets/pids and make sure GitLab can write to them
      "su -c'git -H mkdir tmp/pids/",
      "su -c'git -H mkdir tmp/sockets/",
      "chmod -R u+rwX  tmp/pids/",
      "chmod -R u+rwX  tmp/sockets/",

      # Create public/uploads directory otherwise backup will fail
      "su -c'git -H mkdir public/uploads",
      "chmod -R u+rwX  public/uploads",

      # Copy the example Unicorn config
      "su -c'cp config/unicorn.rb.example config/unicorn.rb'",

      # Enable cluster mode if you expect to have a high load instance
      # Ex. change amount of workers to 3 for 2GB RAM server
      "su -c'editor config/unicorn.rb'",

      # Configure Git global settings for git user, useful when editing via web
      # Edit user.email according to what is set in gitlab.yml
      "su -c'git config --global user.name \"GitLab\"'",
      "su -c'git config --global user.email \"gitlab@localhost\"'",
      "su -c'git config --global core.autocrlf input'",

      # Important Note: Make sure to edit both gitlab.yml and unicorn.rb to match your setup.
      # Configure GitLab DB settings

      # Mysql
      "su -c'cp config/database.yml.mysql config/database.yml'",

      # Make sure to update username/password in config/database.yml.
      # You only need to adapt the production settings (first part).
      # If you followed the database guide then please do as follows:
      # Change 'root' to 'gitlab'
      # Change 'secure password' with the value you have given to $password
      # You can keep the double quotes around the password
      "su -c'editor config/database.yml'",

      # Make config/database.yml readable to git only
      "su -c'chmod o-rwx config/database.yml'",

      # Install Gems

      "cd /home/git/gitlab",

      "sudo gem install charlock_holmes --version '0.6.9.4'",

      # For MySQL (note, the option says "without ... postgres")
      "su -c'bundle install --deployment --without development test postgres aws'",

      # Or for PostgreSQL (note, the option says "without ... mysql")
      "su -c'bundle install --deployment --without development test mysql aws'",

      # Initialize Database and Activate Advanced Features
      "su -c'bundle exec rake gitlab:setup RAILS_ENV=production'",

      # Type 'yes' to create the database.

      # When done you see 'Administrator account created:'

      # Install Init Script

      # Download the init script (will be /etc/init.d/gitlab):

      "cp lib/support/init.d/gitlab /etc/init.d/gitlab",
      "chmod +x /etc/init.d/gitlab",

      # Make GitLab start on boot:

      "update-rc.d gitlab defaults 21",

      # Check Application Status

      # Check if GitLab and its environment are configured correctly:

      "su -c'bundle exec rake gitlab:env:info RAILS_ENV=production'",

      # Start Your GitLab Instance

      # sudo service gitlab start
      # or
      "/etc/init.d/gitlab restart",

      # Double-check Application Status

      #To make sure you didn't miss anything run a more thorough check with:

      "su -c'bundle exec rake gitlab:check RAILS_ENV=production",

      #If all items are green, then congratulations on successfully installing GitLab! However there are still a few steps left.
      #7. Nginx

      #Note: Nginx is the officially supported web server for GitLab. If you cannot or do not want to use Nginx as your web server, have a look at the #GitLab recipes.
      #Installation

      "apt-get install -y nginx",

      #Site Configuration

      #Download an example site config:

      "cp lib/support/nginx/gitlab /etc/nginx/sites-available/gitlab",
      "ln -s /etc/nginx/sites-available/gitlab /etc/nginx/sites-enabled/gitlab",

      # Make sure to edit the config file to match your setup:

      # Change YOUR_SERVER_FQDN to the fully-qualified
      # domain name of your host serving GitLab.
      "editor /etc/nginx/sites-available/gitlab",

      #Restart

      "service nginx restart"



    );
    $this->uninstallCommands = array(
      "apt-get remove -y python python-docutils"
    );
    $this->programDataFolder = "";
    $this->programNameMachine = "gitlab"; // command and app dir name
    $this->programNameFriendly = "!Git Lab!!"; // 12 chars
    $this->programNameInstaller = "Git Lab";
    $this->registeredPreInstallFunctions = array("executeDependencies");
    $this->initialize();
  }

  private function executeDependencies() {
    $gitTools = new \Model\GitTools($this->params);
    $gitTools->install();
  }

}