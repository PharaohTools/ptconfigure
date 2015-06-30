#!/bin/sh
# cm bastion
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-bastion-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-bastion-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-bastion-invoke-bastion.php
# cm git
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-git-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-git-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-git-invoke-git.php
# cm build server
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-build-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-build-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-build-invoke-build-server.php

# cm staging db primary
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-db-primary-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-db-primary-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-db-primary-invoke-db-primary.php
# cm staging db nodes
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-db-secondary-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-db-secondary-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-db-secondary-invoke-db-node.php
# cm staging web nodes
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-web-nodes-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-web-nodes-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-web-nodes-invoke-web-node.php
# cm staging load balancer
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-load-balancer-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-load-balancer-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-staging-load-balancer-invoke-load-balancer.php

# cm production db primary
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-db-primary-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-db-primary-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-db-primary-invoke-db-primary.php
# cm production db nodes
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-db-secondary-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-db-secondary-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-db-secondary-invoke-db-node.php
# cm production web nodes
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-web-nodes-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-web-nodes-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-web-nodes-invoke-web-node.php
# cm production load balancer
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-load-balancer-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-load-balancer-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/medium-production-load-balancer-invoke-load-balancer.php
