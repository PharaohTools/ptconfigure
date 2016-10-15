Logging log
  log-message "Lets configure for Pharaoh Source"

Mkdir path
  label "Ensure the Repositories Directory exists"
  path "{{{ Facts::~::factGetConstant::REPODIR }}}"

Chmod path
  label "Ensure the Repositories Directory is writable"
  path "{{{ Facts::~::factGetConstant::REPODIR }}}"
  recursive true
  mode '0755'

Logging log
  log-message "Configuration Management for Pharaoh Source Complete"