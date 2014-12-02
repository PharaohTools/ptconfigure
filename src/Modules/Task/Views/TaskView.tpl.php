<?php

if (is_array($pageVars["result"])) {
    foreach($pageVars["result"] as $line) { echo "$line\n" ;} }
else {
    var_dump($pageVars["result"]) ;
    echo "Unreadable output\n" ; }

?>

------------------------------
Task Finished