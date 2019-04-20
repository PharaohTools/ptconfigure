<?php

if (!isset($variables)) {
    $variables = array();
}

if (isset($params)) {
    $variables = array_merge($variables, $params) ;
}

$variables['user_name'] = 'ptv' ;
$variables['user_pass'] = 'ptv' ;
$variables['full_user'] = "Pharaoh Virtualize" ;
$variables['locale'] = 'en_GB' ;
$variables['country'] = 'GB' ;
$variables['language'] = 'EN' ;
$variables['gui_mode'] = 'gui' ;
$variables['notify_delay'] = '60' ;

$variables['vm_name'] = 'ptv_bakery_temp_vm' ;
$variables['vm_full_name'] = $variables['os'].' '.$variables['os_full_version'].' Server Edition 64 Bit' ;
$variables['vm_description']  = 'This is an addition to the vanilla install of '.$variables['os'].' ' ;
$variables['vm_description'] .= $variables['os_full_version'].', 64Bit Architecture, ' ;
$variables['vm_description'] .= 'Server Edition. This box contains the same configuration as that one, and also includes ' ;
$variables['vm_description'] .= 'Virtualbox Guest Packages, PHP with some standard modules, and Pharaoh Configure.' ;
