#!/bin/sh
# cm bastion
cleopatra autopilot install build/config/cleopatra/autopilots/medium-bastion-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-bastion-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-bastion-invoke-bastion.php
# cm git
cleopatra autopilot install build/config/cleopatra/autopilots/medium-git-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-git-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-git-invoke-git.php
# cm build server
cleopatra autopilot install build/config/cleopatra/autopilots/medium-jenkins-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-jenkins-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-jenkins-invoke-build-server.php

# cm staging db primary
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-db-primary-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-db-primary-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-db-primary-invoke-db-primary.php
# cm staging db nodes
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-db-secondary-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-db-secondary-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-db-secondary-invoke-db-node.php
# cm staging web nodes
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-web-nodes-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-web-nodes-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-web-nodes-invoke-web-node.php
# cm staging load balancer
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-load-balancer-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-load-balancer-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-staging-load-balancer-invoke-load-balancer.php

# cm production db primary
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-db-primary-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-db-primary-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-db-primary-invoke-db-primary.php
# cm production db nodes
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-db-secondary-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-db-secondary-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-db-secondary-invoke-db-node.php
# cm production web nodes
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-web-nodes-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-web-nodes-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-web-nodes-invoke-web-node.php
# cm production load balancer
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-load-balancer-prep-ubuntu.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-load-balancer-invoke-cleo-dapper-new.php
cleopatra autopilot install build/config/cleopatra/autopilots/medium-production-load-balancer-invoke-load-balancer.php
