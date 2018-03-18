Logging log
  log-message "Lets configure basic settings for Pharaoh Source"

Mkdir path
  label "Ensure the Repositories Directory exists"
  path "{{{ Facts::Runtime::factGetConstant::REPODIR }}}"
  recursive

Chmod path
  label "Ensure the Repositories Directory is writable"
  path "{{{ Facts::Runtime::factGetConstant::REPODIR }}}"
  recursive
  mode 0755

Logging log
  log-message "Basic Configuration Management for Pharaoh Source Complete"