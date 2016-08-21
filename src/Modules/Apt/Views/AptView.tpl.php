Apt Modifications: <?php echo $pageVars["appName"] ; ?>: <?php
  $result_summary = ($pageVars["result"] == true) ? "Success" : "Failure" ;
  echo $result_summary ;
  if (is_string($pageVars["result"])) {
    echo $pageVars["result"] ; } ?>