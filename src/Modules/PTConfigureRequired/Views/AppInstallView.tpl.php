Single App Installer:
--------------------------------------------
<?php

if (isset($pageVars["appName"]) && isset($pageVars["result"])) {

    echo  $pageVars["appName"] ; ?>: <?php
    $result_summary = (strlen($pageVars["result"])>0) ? "Success" : "Failure" ;
    echo $result_summary."\n" ;
}

else {
    echo "No Data.\n";
}
?>
------------------------------
Installer Finished