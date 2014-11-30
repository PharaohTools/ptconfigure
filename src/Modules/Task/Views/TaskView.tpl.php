<?php

if (is_array($pageVars["result"])) {
    foreach($pageVars["result"] as $line) { echo "$line\n" ;} }
else {
    echo "Unreadable output\n" ; }

?>

------------------------------
Task Finished