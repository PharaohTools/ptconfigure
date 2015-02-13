#!/bin/sh
# cm bastion
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-bastion-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-bastion-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-bastion-invoke-bastion.php
# cm git
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-git-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-git-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-git-invoke-git.php
# cm build server
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-jenkins-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-jenkins-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-jenkins-invoke-build-server.php
# cm staging
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-staging-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-staging-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-staging-invoke-standalone-server.php
# cm prod
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-prod-prep-ubuntu.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-prod-invoke-cleo-dapper-new.php
ptconfigure autopilot install build/config/ptconfigure/autopilots/tiny-prod-invoke-standalone-server.php