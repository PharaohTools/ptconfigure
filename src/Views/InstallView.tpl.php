<?php

if ($pageVars["route"]["action"]=="autopilot")
  if (!isset($pageVars["autoPilotErrors"])) {
?>

Git Clone/Checkout: <?php echo $pageVars["gitCheckoutResult"] ; ?>

Git Project Deletion: <?php echo $pageVars["gitDeletorResult"] ; ?>


Project Container: <?php echo $pageVars["projectContainerResult"] ; ?>

Project Initialize: <?php echo $pageVars["projectInitResult"] ; ?>

Project Build Install: <?php echo $pageVars["projectBuildResult"] ; ?>


Host File Entry Creation: <?php echo $pageVars["hostEditorAdditionResult"] ; ?>

Host File Entry Deletion: <?php echo $pageVars["hostEditorDeletionResult"] ; ?>


Virtual Host Creation: <?php echo $pageVars["virtualHostCreatorResult"]  ; ?>

Virtual Host Deletion: <?php echo $pageVars["virtualHostDeletionResult"] ; ?>


Database Reset: <?php echo $pageVars["dbResetResult"]  ; ?>

Database Configure: <?php echo $pageVars["dbConfigureResult"]  ; ?>

Database Drop: <?php echo $pageVars["dbDropResult"] ; ?>

Database Install: <?php echo $pageVars["dbInstallResult"]  ; ?>


Cuke Configure: <?php echo $pageVars["cukeConfAdditionResult"]  ; ?>

Cuke Reset: <?php echo $pageVars["cukeConfDeletionResult"]  ; ?>


Versioning: <?php echo $pageVars["versioningResult"]  ; ?>


<?php
} else {
?>
Auto Pilot errors:
<?php echo $pageVars["autoPilotErrors"]; ?>
<?php

} else if ($pageVars["route"]["action"]=="cli") {
?>
Installer Cli
<?php
} ?>

------------------------------
Installer Finished