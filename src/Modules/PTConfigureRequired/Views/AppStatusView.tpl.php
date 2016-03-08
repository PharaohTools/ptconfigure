<?php echo $pageVars["appName"] ; ?>: <?php
  $result_summary = (strlen($pageVars["appStatusResult"])>0) ? "Installed" : "Not Installed" ;
  echo $result_summary ; ?>