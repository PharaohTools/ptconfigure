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
  label "Install apache Mod Auth External for Debian"
  package-name libapache2-mod-authnz-external
  packager Apt
  when "{{{ Param::is_debian }}}"

RunCommand install
  label "Enable apache Mod Auth External for Debian"
  guess
  command "a2enmod authnz_external"
  when "{{{ Param::is_debian }}}"

PackageManager pkg-install
  label "Install apache Mod Auth External for Redhat"
  package-name mod_authnz_external
  packager Yum
  when "{{{ Param::is_redhat }}}"

PackageManager pkg-install
  label "Install apache PWAuth for Redhat"
  package-name pwauth
  packager Yum
  when "{{{ Param::is_redhat }}}"

RunCommand install
  label "Enable apache Mod Auth External for Redhat"
  guess
  command "a2enmod authnz_external"
  when "{{{ Param::is_redhat }}}"

Copy put
  label "{{{ Parameter::app-slug }}} Apache Custom Authentication method Conf file"
  source "{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}ptconfigure/ptconfigure/src/Modules/PTSource/Templates/{{{ Parameter::app-slug }}}_auth.conf"
  target "/etc/apache2/conf-available/{{{ Parameter::app-slug }}}_auth.conf"

RunCommand install
  label "Enable apache Mod Auth External"
  guess
  command "a2enconf {{{ Parameter::app-slug }}}_auth"

Logging log
  log-message "Configuration Management for Pharaoh Source Complete"