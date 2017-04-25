<?php

$json = array (
     "Operating System: " => $pageVars["result"]->os ,
     "Linux Type: " => $pageVars["result"]->linuxType ,
     "Distro: " => $pageVars["result"]->distro ,
     "Version: " => $pageVars["result"]->version ,
     "Architecture: " => $pageVars["result"]->architecture ,
     "Host Name: " => $pageVars["result"]->hostName ,
) ;

echo json_encode($json)."\n";

?>