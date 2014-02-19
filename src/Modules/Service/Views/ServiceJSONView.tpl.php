<?php

  $result = (strlen($pageVars["appInstallResult"])>0) ? "Success" : "Failure" ;

  $jsonArray = array(
    "appName" => $pageVars["appName"] ,
    "appInstallResult" => $result,
    "appInstallOutput" => $pageVars["appInstallResult"],
  );

  echo json_encode($jsonArray)."\n";

?>