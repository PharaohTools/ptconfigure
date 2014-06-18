<?php

$parent = dirname(__FILE__).'/' ;

$prefix = "default-project" ;
$priv_ssh_key = "/home/golden/.ssh/id_rsa" ;
$provider = "VSphere" ;
$image_id = "3101045" ;
$region_id = "2" ;
$size_id = "66" ;
$user_name = "root" ;
$box_amount = "1" ;
$wait_time = "900" ; // wait 15 minutes if needed for boxes to complete

$priv_ssh_key_bastion = $priv_ssh_key_git = $priv_ssh_key_jenkins = $priv_ssh_key_db_nodes = $priv_ssh_key_db_balancer =
$priv_ssh_key_web_nodes = $priv_ssh_key_load_balancer = $priv_ssh_key  ;

$provider_bastion = $provider_git = $provider_jenkins = $provider_db_nodes = $provider_db_balancer =
$provider_web_nodes = $provider_load_balancer = $provider ;

$image_id_bastion = $image_id_git = $image_id_jenkins = $image_id_db_nodes = $image_id_db_balancer =
$image_id_web_nodes = $image_id_load_balancer = $image_id ;

$region_id_bastion = $region_id_git = $region_id_jenkins = $region_id_db_nodes = $region_id_db_balancer =
$region_id_web_nodes = $region_id_load_balancer = $region_id ;

$size_id_bastion = $size_id_git = $size_id_jenkins = $size_id_db_nodes = $size_id_db_balancer = $size_id_web_nodes =
$size_id_load_balancer = $size_id ;

$size_id_jenkins = "62" ; // Jenkins is larger as behat was getting memory issues on install

$user_name_bastion = $user_name_git = $user_name_jenkins = $user_name_db_nodes = $user_name_db_balancer = $user_name_web_nodes =
$user_name_load_balancer = $user_name ;

$box_amount_bastion = $box_amount_git = $box_amount_jenkins = $box_amount_db_nodes =
$box_amount_db_balancer = $box_amount_web_nodes = $box_amount_load_balancer = $box_amount ;
$box_amount_web_nodes = "2" ;
$box_amount_db_nodes = "2" ;