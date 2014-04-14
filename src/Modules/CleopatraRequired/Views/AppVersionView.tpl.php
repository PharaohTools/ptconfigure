Single App Version Check:
--------------------------------------------


<?php

if (isset($pageVars["appName"]) && isset($pageVars["appInstallResult"])) {

    echo  $pageVars["appName"]." Version : " ;
    echo $pageVars["appInstallResult"]."\n" ;
}

else {
    echo "No Data.\n";
}
?>

------------------------------
Version Check Finished