<?php

  $result = (strlen($pageVars["appStatusResult"])>0) ? "Installed" : "Not Installed" ;

  $jsonArray = array(
    "appName" => $pageVars["appName"] ,
    "appInstallResult" => $result,
    "appInstallOutput" => $pageVars["appStatusResult"],
  );

  echo json_encode($jsonArray)."\n";

?>