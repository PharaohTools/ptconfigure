Logging log
  source "Domain DSL"

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain exists in the account"
  guess
  domain-name "{{{ var::domain }}}"
  domain-email webmaster@pharaohtools.com
  domain-comment PharaohToolsGroup
  domain-ttl 3600

Rackspace ensure-record-exists
  label "Lets ensure our A record {{{ var::env_id_slug }}}.$$domain points to our Domain $$domain, a target of {{{ Facts::Environment::findTargetFrom::$$gen_env_name }}}"
  guess
  domain-name "{{{ var::domain }}}"
  record-type A
  record-name "{{{ var::subdomain }}}.$$domain"
  record-data "{{{ Facts::Environment::findTargetFrom::$$gen_env_name }}}"
  record-ttl 3600
  disable-duplicates

Rackspace ensure-record-exists
  label "Lets ensure our A record {{{ var::env_id_slug }}}.$$domain points to our Domain $$domain, a target of {{{ Facts::Environment::findTargetFrom::$$gen_env_name }}}"
  guess
  domain-name "{{{ var::domain }}}"
  record-type A
  record-name "{{{ var::server_subdomain }}}.$$domain"
  record-data "{{{ Facts::Environment::findTargetFrom::$$gen_env_name }}}"
  record-ttl 3600
  disable-duplicates
  when "{{{ param::is_isophp }}}"

Logging log
  source "Domain DSL"