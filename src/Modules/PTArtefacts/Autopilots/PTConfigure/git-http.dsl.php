Logging log
  log-message "Lets configure HTTP Git Settings for Pharaoh Source"

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

RunCommand Install
  label "Clone momdauthnz external"
  guess
  command "rm -rf /opt/mod-auth-external && cd /opt/ && git clone https://github.com/phokz/mod-auth-external.git"
  when "{{{ PTArtefacts::~::isS390xArch }}}"

LinuxCompiler directory
  label "Compile Mod Auth External for Redhat"
  directory /opt/mod-auth-external/mod_authnz_external
  when "{{{ PTArtefacts::~::isS390xArch }}}"

#PackageManager pkg-install
#  label "Install apache PWAuth for Redhat"
#  package-name pwauth
#  packager Yum
#  when "{{{ Param::is_redhat }}}"
#
#RunCommand install
#  label "Enable apache Mod Auth External for Redhat"
#  guess
#  command "a2enmod authnz_external"
#  when "{{{ Param::is_redhat }}}"

Copy put
  label "{{{ Parameter::app-slug }}} Apache Custom Authentication method Conf file to Debian Directory"
  source "{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}ptconfigure/ptconfigure/src/Modules/PTArtefacts/Templates/{{{ Parameter::app-slug }}}_auth.conf"
  target "/etc/apache2/conf-available/{{{ Parameter::app-slug }}}_auth.conf"
  when "{{{ Param::is_debian }}}"

Copy put
  label "{{{ Parameter::app-slug }}} Apache Custom Authentication method Conf file to Redhat Directory"
  source "{{{ Facts::Runtime::factGetConstant::PFILESDIR }}}ptconfigure/ptconfigure/src/Modules/PTArtefacts/Templates/{{{ Parameter::app-slug }}}_auth.conf"
  target "/etc/httpd/conf.d/{{{ Parameter::app-slug }}}_auth.conf"
  when "{{{ Param::is_redhat }}}"

RunCommand install
  label "Enable apache Mod Auth External"
  guess
  command "a2enconf {{{ Parameter::app-slug }}}_auth"
  when "{{{ Param::is_debian }}}"

Logging log
  log-message "Configuration Management for HTTP Git Settings for Pharaoh Source Complete"