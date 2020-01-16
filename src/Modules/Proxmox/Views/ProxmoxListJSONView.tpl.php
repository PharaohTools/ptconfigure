<?php
//var_dump($pageVars) ;
$json_data = json_encode([]) ;

if ($pageVars['params']['type'] == 'virtual_machines') {
    $json_data = json_encode($pageVars['result']) ;
}

echo $json_data ;
?>