Install Custom Package - <?php echo $pageVars["package-friendly"] ; ?> Installer:
--------------------------------------------

<?php

foreach ($pageVars["results"] as $installResult) {
  echo $installResult["appName"] . ': ' ;
  $result = ($installResult["installResult"] == true) ? "Success" : "Failure" ;
  echo $result."\n" ;
}

?>

------------------------------
Installer Finished