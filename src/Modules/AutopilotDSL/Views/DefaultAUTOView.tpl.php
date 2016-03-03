<?php

  if (isset($pageVars["appName"])) {
      echo $pageVars["appName"] ; ?>: <?php }
  $result_summary = ($pageVars["result"] == true) ? "Success" : "Failure" ;
  echo $result_summary."\n" ;
if (isset($pageVars["result"]) && is_string($pageVars["result"])) {
    echo $pageVars["result"]."\n" ; }
//if (isset($pageVars["result"]) && !is_string($pageVars["result"])) {
//    var_dump($pageVars["result"]) ; }
?>