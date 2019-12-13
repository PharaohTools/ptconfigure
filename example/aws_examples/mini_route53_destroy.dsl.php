Logging log
  source "Destroy Domain DSL"

AWSRoute53 ensure-domain-empty
  label "Lets ensure our domain $$domain does not exist in the account"
  guess
  domain-name "$$domain"
  domain-ttl 3600
  aws-access-key "$$aws-access-key"
  aws-secret-key "$$aws-secret-key"
  aws-region "$$aws-region"
``
Logging log
  source "Destroy Domain DSL"