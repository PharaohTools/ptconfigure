Logging log
  log-message "Lets configure PHP and Files for Pharaoh Web Application"

User ensure-exists
  when "{{ PTWebApplication::~::isNotOSX }}"
  label "Ensure {{{ Parameter::app-slug }}} user exists"
  username "{{{ Parameter::app-slug }}}"
  fullname "{{{ Parameter::app-slug }}}"
  home-directory ""
  shell "/bin/bash"

PHPModules ensure
  label "Ensure PHP Default Modules are installed"

PHPDefaults install
  label "Ensure PHP Default Settings are okay"

ApacheDefaults install
  label "Ensure Apache Default Settings are okay"

PHPFPM ensure
  label "Ensure PHP FPM is installed"

PTDeploy ensure
  label "Ensure Pharaoh Deploy is installed"

ApacheFastCGIModules ensure
  label "Ensure Apache Fast CGI is installed"

Chmod path
  label "Make the PT Web Application Settings file writable"
  path '{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}{{{ Parameter::app-slug }}}/{{{ Parameter::app-slug }}}/{{{ Parameter::app-slug }}}vars'
  mode "0755"

Chown path
  label "Ensure the Pharaoh Web Application user owns the Program Files"
  path '{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}{{{ Parameter::app-slug }}}/{{{ Parameter::app-slug }}}/'
  recursive true
  user '{{{ Parameter::app-slug }}}'

Chgrp path
  label "Ensure the Pharaoh Group user owns the Program Files"
  path '{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}{{{ Parameter::app-slug }}}/'
  recursive true
  group '{{{ Parameter::app-slug }}}'

Templating install
  label "{{{ Parameter::app-slug }}} PHP FPM Pool Config"
  source "{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}ptconfigure/ptconfigure/src/Modules/PTWebApplication/Templates/ptapplication_pool.tpl.php"
  target "{{{ PTWebApplication::~::getFPMPoolDir }}}/{{{ Parameter::app-slug }}}.conf"
  template_app-slug "{{{ Parameter::app-slug }}}"
  template_fpm-port "{{{ Parameter::fpm-port }}}"

PHPFPM restart
  label "{{{ Parameter::app-slug }}} PHP FPM Restart"

File should-exist
  file "/var/log/pharaoh.log"

Logging log
  log-message "Configuration Management for Pharaoh Web Application Complete"
