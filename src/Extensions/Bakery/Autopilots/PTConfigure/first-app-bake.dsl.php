Logging log
  log-message "Lets Bake an ISO"

User ensure-exists
  when "{{{ PTWebApplication::~::isNotOSX }}}"
  label "Ensure {{{ Parameter::app-slug }}} user exists"
  username "{{{ Parameter::app-slug }}}"
  fullname "{{{ Parameter::app-slug }}}"
  home-directory ""
  shell "/bin/bash"

ApacheServer ensure
  label "Ensure Apache Server is installed"