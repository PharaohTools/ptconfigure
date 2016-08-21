<?php

  $result = (strlen($pageVars["result"])>0) ? "Success" : "Failure" ;

  $jsonArray = array(
    "appName" => $pageVars["appName"] ,
    "result" => $result,
    "appInstallOutput" => $pageVars["result"],
  );

  echo json_encode($jsonArray)."\n";

?>