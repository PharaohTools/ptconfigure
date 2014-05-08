Single App Version Check:
--------------------------------------------


<?php

if (isset($pageVars["appName"]) && isset($pageVars["versionResult"])) {

    if (is_object($pageVars["versionResult"]) && $pageVars["versionResult"] instanceof \Model\SoftwareVersion) {

        echo  $pageVars["appName"];
        if (isset($pageVars["params"]["version-type"]) &&
            in_array($pageVars["params"]["version-type"], array("Installed", "installed", "Recommended", "recommended", "Latest", "latest"))) {
            echo " ".ucfirst($pageVars["params"]["version-type"])." Version : \n" ;
            echo "    Full Version: ".$pageVars["versionResult"]->fullVersionNumber."\n" ;
            echo "    Short Version: ".$pageVars["versionResult"]->shortVersionNumber."\n" ; }
        else {
            echo " Installed Version : \n" ;
            echo "    Full Version: ".$pageVars["versionResult"]->fullVersionNumber."\n" ;
            echo "    Short Version: ".$pageVars["versionResult"]->shortVersionNumber."\n" ; }
    }

    else {
        echo  $pageVars["appName"];
        if (isset($pageVars["params"]["version-type"]) &&
            in_array($pageVars["params"]["version-type"], array("Installed", "installed", "Recommended", "recommended", "Latest", "latest"))) {
            echo " ".ucfirst($pageVars["params"]["version-type"]) ; }
        echo " Version : " ;
        echo ($pageVars["versionResult"] == false) ? "No Result" : $pageVars["versionResult"]."\n" ;
    }

}

else {
    echo "No Data.\n";
}
?>

------------------------------
Version Check Finished