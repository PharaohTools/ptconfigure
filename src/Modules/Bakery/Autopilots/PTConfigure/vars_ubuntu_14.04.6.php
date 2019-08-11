<?php

if (!isset($variables)) {
    $variables = array();
}

if (isset($params)) {
    $variables = array_merge($variables, $params) ;
}

$variables['os'] = 'Ubuntu' ;
$variables['os_type'] = 'Ubuntu_64' ;
$variables['os_major_version'] = '14' ;
$variables['os_minor_version'] = '04' ;
$variables['os_build_version'] = '6' ;
$variables['os_slugvar'] = 'ubuntu_14.04.6' ;
$variables['os_full_version'] = $variables['os_major_version'].'.'.$variables['os_minor_version'].'.'.$variables['os_build_version'] ;
$variables['os_vbox_version'] = $variables['os_major_version'].'.'.$variables['os_minor_version'] ;
$variables['os_group'] = "server-64bit" ;
$variables['iso_file_remote_location']  = "http://releases.ubuntu.com/";
$variables['iso_file_remote_location'] .= "{$variables['os_major_version']}.{$variables['os_minor_version']}/";
$variables['iso_file_remote_location'] .= "ubuntu-{$variables['os_major_version']}.{$variables['os_minor_version']}";
$variables['iso_file_remote_location'] .= ".{$variables['os_build_version']}";
$variables['iso_file_remote_location'] .= "-server-amd64.iso";
$variables['iso_path']  = "{{{ Facts::Runtime::factGetEnvVar::HOME }}}/Downloads/ubuntu-";
$variables['iso_path'] .= "{$variables['os_major_version']}.{$variables['os_minor_version']}";
$variables['iso_path'] .= ".{$variables['os_build_version']}";
$variables['iso_path'] .= "-server-amd64.iso";