<?php

# Input

$headers[] = 'Content-Type: application/json' ;
$headers[] = 'Authorization: Bearer b7d03a6947b217efb6f3ec3bd3504582' ;

# Process
$aws_region = '' ;

$command =
'ptconfigure-enterprise AWSRoute53 ensure-record-exists '.
'--domain-name="$$domain" '.
'--record-type=A '.
'--record-name="test.$$domain" '.
'--record-data="$$ptip" '.
'--record-ttl=3600 '.
'--aws-access-key="$$aws-access-key" '.
'--aws-secret-key="$$aws-secret-key" '.
'--aws-region="'.$aws_region.'" '.
'--disable-duplicates -yg' ;




# Output

header('content-type: application/json; charset=utf-8');
header('status: 201 Created');
header('ratelimit-limit: 1200');
header('ratelimit-remaining: 1120');
header('ratelimit-reset: 1415984218');

$record_details =[
    "id" => 28448433,
    "type" => "A",
    "name" => "www",
    "data" => "162.10.66.0",
    "priority" => null,
    "port" => null,
    "ttl" => 1800,
    "weight" => null,
    "flags" => null,
    "tag" => null
] ;
$response_array = ['domain_record' => $record_details ] ;

echo json_encode($response_array) ;