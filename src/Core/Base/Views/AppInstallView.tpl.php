Single App Installer:
--------------------------------------------

<?php echo $pageVars["appName"] ; ?>: <?php

  $result = ($pageVars["appInstallResult"]==true) ? "Success" : "Failure" ;
  echo $result ;
?>

------------------------------
Installer Finished