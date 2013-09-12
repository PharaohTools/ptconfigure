<?php

  $viewResults = array();

  foreach ($pageVars["results"] as $installResult) {
    $result = ($installResult["installResult"] == true) ? "Success" : "Failure" ;
    $viewResults[] = array($installResult["appName"] => $result);
  }

  echo json_encode($viewResults)."\n";

?>