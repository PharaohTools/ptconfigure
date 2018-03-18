Logging log
  log-message "Lets configure PHP and Files for Pharaoh Source"

Mkdir path
  label "Ensure the Repositories Directory exists"
  path "{{{ Facts::Runtime::factGetConstant::REPODIR }}}"

Chmod path
  label "Ensure the Repositories Directory is writable"
  path "{{{ Facts::Runtime::factGetConstant::REPODIR }}}"
  recursive true
  mode '0755'

RunCommand install
  guess
  command "git config --global http.https://{{{ Param::vhe-url }}}.sslCAInfo '/etc/ssl/certificates/fullchain.pem'"
  when "{{{ Param::enable-ssl }}}"

RunCommand install
  guess
  command "git config --global http.https://{{{ Param::vhe-url }}}.sslCert '/etc/ssl/certificates/cert.pem'"
  when "{{{ Param::enable-ssl }}}"

RunCommand install
  guess
  command "git config --global http.https://{{{ Param::vhe-url }}}.sslKey '/etc/ssl/certificates/private.pem'"
  when "{{{ Param::enable-ssl }}}"

Logging log
  log-message "Configuration Management for Pharaoh Source Complete"