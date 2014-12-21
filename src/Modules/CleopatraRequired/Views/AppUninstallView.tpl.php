Single App Uninstaller:
------------------------------
<?php echo $pageVars["appName"] ; ?>: <?php
  $result_summary = (strlen($pageVars["result"])>0) ? "Success" : "Failure" ;
  echo $result_summary."\n" ;
?>
------------------------------
Installer Finished