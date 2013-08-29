Development Client Preconfigured Installer:
--------------------------------------------

<?php

  foreach ($pageVars["results"] as $installResult) {
    echo $installResult["appName"] . ': ' ;
    $result = ($installResult["installResult"] == true) ? "Success" : "Failure" ;
    echo $result ;
  }

?>

------------------------------
Installer Finished