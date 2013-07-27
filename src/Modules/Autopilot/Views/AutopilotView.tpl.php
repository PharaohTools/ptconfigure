Autopilot Install - <?php echo $pageVars["package-friendly"] ; ?> Installer:
--------------------------------------------

<?php

foreach ($pageVars["results"] as $installResult) {
  echo $installResult["stepName"] . ': ' ;
  $result = ($installResult["installResult"] == true) ? "Success" : "Failure" ;
  echo $result."\n" ;
}

?>

------------------------------
Installer Finished