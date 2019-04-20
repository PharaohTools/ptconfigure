<?php

if (isset($params)) {
    $variables = array_merge($variables, $params) ;
}

$variables['vm_name'] = 'ptv_bakery_temp_vm' ;
$variables['var_os'] = 'Ubuntu' ;
$variables['var_os_version'] = '18.04.2' ;
$variables['var_os_group'] = "server-64bit" ;
$variables['iso_file_remote_location'] = "";
$variables['vm_full_name'] = $variables['var_os'].' '.$variables['var_os_version'].' Server Edition 64 Bit' ;
$variables['vm_description']  = 'This is an addition to the vanilla install of '.$variables['var_os'].' ' ;
$variables['vm_description'] .= $variables['var_os_version'].', 64Bit Architecture, ' ;
$variables['vm_description'] .= 'Server Edition. This box contains the same configuration as that one, and also includes ' ;
$variables['vm_description'] .= 'Virtualbox Guest Packages, PHP with some standard modules, and Pharaoh Configure.' ;
