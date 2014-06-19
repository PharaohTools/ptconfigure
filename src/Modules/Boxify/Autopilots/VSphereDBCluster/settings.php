<?php

$parent = dirname(__FILE__).'/' ;

$prefix = "daves" ;
$suffix = ".eurweb.eidos.com" ;
$priv_ssh_key = "install" ;
$provider = "VSphere" ;
$source_vm_id = "vm-164" ;
$user_name = "install" ;
$box_amount = "1" ;
$wait_time = "900" ; // wait 15 minutes if needed for boxes to complete

$priv_ssh_key_db_nodes = $priv_ssh_key_db_balancer = $priv_ssh_key  ;
$provider_db_nodes = $provider_db_balancer = $provider ;
$source_vm_id_db_nodes = $source_vm_id_db_balancer = $source_vm_id ;
$user_name_db_nodes = $user_name_db_balancer = $user_name ;
$box_amount_db_nodes = $box_amount_db_balancer = $box_amount ;
$box_amount_db_nodes = "5" ; // This overrides the number of DB Nodes to 3, the minimum Galera recommends for failover is 5