<?php

if (!isset($variables)) {
    $variables = array();
}

if (isset($params)) {
    $variables = array_merge($variables, $params) ;
}

$variables['os_name'] = 'Ubuntu' ;
$variables['os_type'] = 'Ubuntu_64' ;
$variables['os_major_version'] = '18' ;
$variables['os_minor_version'] = '04' ;
$variables['os_build_version'] = '4' ;
$variables['os_full_version'] = $variables['os_major_version'].'.'.$variables['os_minor_version'].'.'.$variables['os_build_version'] ;
$variables['os_vbox_version'] = $variables['os_major_version'].'.'.$variables['os_minor_version'] ;
$variables['os_slug'] = $variables['os_major_version'].'.'.$variables['os_minor_version'] ;

$variables['os_group'] = "server-64bit" ;
$variables['iso_file_remote_location'] = "http://cdimages.ubuntu.com/ubuntu/releases/bionic/release/ubuntu-18.04.4-server-amd64.iso";
$variables['iso_path'] = "/opt/ptvirtualize/boxes/ubuntu-18.04.4-server-amd64.iso";