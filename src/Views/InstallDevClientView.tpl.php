PHP Unit: <?php echo $pageVars["phpUnitInstallResult"] ; ?>

<?php
if ($pageVars["extraParams"][0]=="silent") {
  ?>
  Silent Genererated Installer Values
<?php
} else if ($pageVars["extraParams"][0]=="autogen") {
  ?>
  Auto Genererated Installer Values
<?php
} ?>

------------------------------
Installer Finished