Logging log
  log-message "Lets configure SSL for Pharaoh Source"

RunCommand install
  guess
  command "git config --global http.https://{{{ Param::vhe-url }}}.sslCAInfo '/etc/ssl/certificates/fullchain.pem'"
  when "{{{{ Param::enable-ssl }}}"

RunCommand install
  guess
  command "git config --global http.https://{{{ Param::vhe-url }}}.sslCert '/etc/ssl/certificates/cert.pem'"
  when "{{{{ Param::enable-ssl }}}"

RunCommand install
  guess
  command "git config --global http.https://{{{ Param::vhe-url }}}.sslKey '/etc/ssl/certificates/private.pem'"
  when "{{{{ Param::enable-ssl }}}"

Logging log
  log-message "Configuration Management for Pharaoh Source Complete"