Single App Installer:
--------------------------------------------

<?php echo $pageVars["appName"] ; ?>: <?php

  $result_summary = (strlen($pageVars["appInstallResult"])>0) ? "Success" : "Failure" ;
  echo $result_summary."\n\n" ;
  echo $pageVars["appInstallResult"] ;
?>

------------------------------
Installer Finished