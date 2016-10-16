Logging log
  log-message "Lets configure for Pharaoh Source"

Mkdir path
  label "Ensure the Repositories Directory exists"
  path "{{{ Facts::Runtime::factGetConstant::REPODIR }}}"
  recursive

Chmod path
  label "Ensure the Repositories Directory is writable"
  path "{{{ Facts::Runtime::factGetConstant::REPODIR }}}"
  recursive
  mode 0755

PackageManager pkg-install
  label "Install apache Mod Auth External"
  package-name libapache2-mod-authnz-external
  packager Apt

Templating install
  label "{{{ Parameter::app-slug }}} PHP FPM Pool Config"
  source "{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}{{{ Parameter::app-slug }}}/{{{ Parameter::app-slug }}}/src/Modules/PTSource/Templates/{{{ Parameter::app-slug }}}_auth.conf"
  target "/etc/apache2/conf-available/{{{ Parameter::app-slug }}}_auth.conf"
  template_app-slug "{{{ Parameter::app-slug }}}"

RunCommand install
  guess
  command "a2enmod authnz_external'"

Logging log
  log-message "Configuration Management for Pharaoh Source Complete"