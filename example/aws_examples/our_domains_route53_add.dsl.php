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
  label "Lets ensure our domain $$domain_one exists in the account"
  guess
  domain-name "$$domain_one"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_one exists in the account"
  guess
  domain-name "$$domain_one"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_two exists in the account"
  guess
  domain-name "$$domain_two"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_three exists in the account"
  guess
  domain-name "$$domain_three"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_four exists in the account"
  guess
  domain-name "$$domain_four"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_five exists in the account"
  guess
  domain-name "$$domain_five"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_six exists in the account"
  guess
  domain-name "$$domain_six"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_seven exists in the account"
  guess
  domain-name "$$domain_seven"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_eight exists in the account"
  guess
  domain-name "$$domain_eight"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_nine exists in the account"
  guess
  domain-name "$$domain_nine"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

AWSRoute53 ensure-domain-exists
  label "Lets ensure our domain $$domain_five exists in the account"
  guess
  domain-name "$$domain_five"
  domain-email webmaster@pharaohtools.com
  domain-comment 'Managed by Pharaoh Tools'
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
  disable-duplicates

Logging log
  source "Domain DSL"