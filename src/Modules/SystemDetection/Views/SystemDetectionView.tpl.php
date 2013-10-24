Systems Detection:
--------------------------------------------

<?php

echo "Operating System: " . $pageVars["result"]->os . "\n" ;
echo "Linux Type: " . $pageVars["result"]->linuxType . "\n" ;
echo "Distro: " . $pageVars["result"]->distro . "\n" ;
echo "Version: " . $pageVars["result"]->version . "\n" ;
echo "Architecture: " . $pageVars["result"]->architecture . "\n" ;
echo "Host Name: " . $pageVars["result"]->hostName . "\n" ;

if (count($pageVars["result"]->ipAddresses)==0) {
    echo "No Detected IP Addresses" ; }

for ($i=0; $i<count($pageVars["result"]->ipAddresses) ; $i++) {
    echo "IP Address $i: " . $pageVars["result"]->ipAddresses[$i] . "\n" ; }

?>

------------------------------
Detection Finished