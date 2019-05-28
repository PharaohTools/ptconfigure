<?php
/*
 * $json_request
 */
$command =
    'ptconfigure-enterprise AWSRoute53 ensure-record-exists '.
    '--domain-name="pharaohtools.com" '.
//    '--domain-name="'.$json_request['name'].'" '.
    '--record-type="'.$json_request['type'].'" '.
    '--record-name="'.$json_request['name'].'" '.
    '--record-data="'.$json_request['data'].'" '.
    '--record-ttl="'.$json_request['ttl'].'" '.
    '--aws-access-key="AKIAIHMYGJVYJAMR5FUA" '.
    '--aws-secret-key="5BEH0ut0iNv5avwV61emNpRudzvCEexEin\/XymJc" '.
    '--aws-region="eu-west-2" '.
    '--disable-duplicates -yg' ;

//ob_start() ;
passthru($command, $return) ;
//$out = ob_get_clean() ;
if ($return != 0) {
    $sahara_id = false ;
} else {
    $sahara_id = time() ;
}

