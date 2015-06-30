<?php

$parent = dirname(__FILE__).'/' ;

$prefix = "default-project" ;
$ssh_key_name = "" ; # REQUIRED THAT YOU ENTER IN A KEY
$priv_ssh_key = "/home/golden/.ssh/id_rsa" ; // Depends on your setup.
$provider = "DigitalOcean" ; // DigitalOcean || DigitalOceanV2 || Rackspace
$image_id = "3101045" ; // DO = , RAX = , AWS = ami-32b46b45
$region_id = "2" ; // DO = 2, RS = LON, AWS = eu-west01

$size_id = "66" ;  // DO = 66, RS = 2
$user_name = "root" ; // This depends on your image
$box_amount = "1" ; //
$wait_time = "900" ; // wait 15 minutes if needed for boxes to complete

$bastion_env = "bastion" ;
$git_env = "git" ;
$build_env = "build" ;
$staging_env = "staging" ;
$production_env = "production" ;

$priv_ssh_key_bastion = $priv_ssh_key_git = $priv_ssh_key_build =
$priv_ssh_key_staging = $priv_ssh_key_production = $priv_ssh_key  ;

$provider_bastion = $provider_git = $provider_build =
$provider_staging = $provider_production = $provider ;

$image_id_bastion = $image_id_git = $image_id_build =
$image_id_staging = $image_id_production = $image_id ;

$region_id_bastion = $region_id_git = $region_id_build =
$region_id_staging = $region_id_production = $region_id ;

$size_id_bastion = $size_id_git = $size_id_build = $size_id_staging =
$size_id_production = $size_id ;
$size_id_build = "62" ; // DO = 62, RS = 3 Jenkins is larger as behat was getting memory issues on install

$user_name_bastion = $user_name_git = $user_name_build =
$user_name_staging = $user_name_production = $user_name ;

$box_amount_bastion = $box_amount_git = $box_amount_build =
$box_amount_staging = $box_amount_production = $box_amount ;