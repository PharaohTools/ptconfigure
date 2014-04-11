Single App Installer:
--------------------------------------------


<?php

if (isset($pageVars["appName"]) && isset($pageVars["appInstallResult"])) {

    echo  $pageVars["appName"] ; ?>: <?php
    $result_summary = (strlen($pageVars["appInstallResult"])>0) ? "Success" : "Failure" ;
    echo $result_summary."\n" ;
}

else {
    echo "No Data.\n";
}
?>

------------------------------
Installer Finished