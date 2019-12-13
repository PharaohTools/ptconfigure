Logging log
  source "Domain DSL"
#
#AWSRoute53 list
#  label "Lets list zones in the account"
#  guess
#  list-type "zones"
#  aws-access-key "$$aws-access-key"
#  aws-secret-key "$$aws-secret-key"
#  aws-region "$$aws-region"

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain exists in the account"
  guess
  domain-name "$$domain"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-record-exists
  label "Lets ensure our A record test.$$domain record points to our Domain $$domain, a target of $$ptip"
  guess
  domain-name "$$domain"
  record-type A
  record-name "test.$$domain"
  record-data "$$ptip"
  record-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

Logging log
  source "Domain DSL"