Single App Version Check:
--------------------------------------------


<?php

if (isset($pageVars["appName"]) && isset($pageVars["appInstallResult"])) {

    echo  $pageVars["appName"];
    if (isset($pageVars["params"]["version-type"]) &&
        in_array($pageVars["params"]["version-type"], array("Installed", "installed", "Recommended", "recommended", "Latest", "latest"))) {
        echo " ".ucfirst($pageVars["params"]["version-type"]) ; }
    echo " Version : " ;
    echo $pageVars["appInstallResult"]."\n" ;
}

else {
    echo "No Data.\n";
}
?>

------------------------------
Version Check Finished