<?php

$parent = dirname(__FILE__).'/' ;

$prefix = "default-project" ;
$priv_ssh_key = "/home/golden/.ssh/id_rsa" ;
$provider = "DigitalOcean" ;
$image_id = "3101045" ;
$region_id = "2" ;
$size_id = "66" ;
$user_name = "root" ;
$box_amount = "1" ;
$wait_time = "900" ; // wait 15 minutes if needed for boxes to complete

$priv_ssh_key_bastion = $priv_ssh_key_git = $priv_ssh_key_jenkins = $priv_ssh_key_secondary_db = $priv_ssh_key_primary_db =
$priv_ssh_key_web_nodes = $priv_ssh_key_load_balancer = $priv_ssh_key  ;

$provider_bastion = $provider_git = $provider_jenkins = $provider_secondary_db = $provider_primary_db =
$provider_web_nodes = $provider_load_balancer = $provider ;

$image_id_bastion = $image_id_git = $image_id_jenkins = $image_id_secondary_db = $image_id_primary_db =
$image_id_web_nodes = $image_id_load_balancer = $image_id ;

$region_id_bastion = $region_id_git = $region_id_jenkins = $region_id_secondary_db = $region_id_primary_db =
$region_id_web_nodes = $region_id_load_balancer = $region_id ;

$size_id_bastion = $size_id_git = $size_id_jenkins = $size_id_secondary_db = $size_id_primary_db = $size_id_web_nodes =
$size_id_load_balancer = $size_id ;

$user_name_bastion = $user_name_git = $user_name_jenkins = $user_name_secondary_db = $user_name_primary_db = $user_name_web_nodes =
$user_name_load_balancer = $user_name ;

$box_amount_bastion = $box_amount_git = $box_amount_jenkins = $box_amount_secondary_db =
$box_amount_primary_db = $box_amount_web_nodes = $box_amount_load_balancer = $box_amount ;
$box_amount_web_nodes = "2" ;
$box_amount_secondary_db = "2" ;