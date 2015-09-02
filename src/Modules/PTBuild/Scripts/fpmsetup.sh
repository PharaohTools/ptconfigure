#!/bin/bash
# ensure fpm and fastcgi are installed
sudo apt-get install libapache2-mod-fastcgi php5-fpm -y
# enable the apache mod_actions

# enable modules
a2enmod actions fastcgi alias -y
# enable conf for php5 fpm
a2enconf php5-fpm
service apache2 reload