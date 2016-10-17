Logging log
  log-message "Lets configure PHP specifically for Pharaoh Source"

File should-have-line
  label "{{{ Parameter::app-slug }}} PHP Config for Memory Limit"
  file "{{{ PTWebApplication::~::getFPMPoolDir }}}{{{ Parameter::app-slug }}}.conf"
  search "php_admin_value[memory_limit] = 1024M "

File should-have-line
  label "{{{ Parameter::app-slug }}} PHP Config for Upload Filesize Limit"
  file "{{{ PTWebApplication::~::getFPMPoolDir }}}{{{ Parameter::app-slug }}}.conf"
  search "php_admin_value[upload_max_filesize] = 1024M "

File should-have-line
  label "{{{ Parameter::app-slug }}} PHP Config for Post Size Limit"
  file "{{{ PTWebApplication::~::getFPMPoolDir }}}{{{ Parameter::app-slug }}}.conf"
  search "php_admin_value[post_max_size] = 1024M "

File should-have-line
  label "{{{ Parameter::app-slug }}} PHP Config for Post Size Limit"
  file "{{{ PTWebApplication::~::getFPMPoolDir }}}{{{ Parameter::app-slug }}}.conf"
  search "php_admin_value[post_max_size] = 1024M "

File should-have-line
  label "{{{ Parameter::app-slug }}} Apache Config for Post Size Limit"
  file "{{{ PTWebApplication::~::getFPMPoolDir }}}{{{ Parameter::app-slug }}}.conf"
  search "php_admin_value[post_max_size] = 1024M "

PHPFPM restart
  label "{{{ Parameter::app-slug }}} PHP FPM Restart"

Logging log
  log-message "PHP Configuration for Pharaoh Source Complete"