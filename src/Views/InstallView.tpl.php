<?php

if ($pageVars["route"]["action"]=="autopilot")
  if (!isset($pageVars["autoPilotErrors"])) {
?>

Git Clone/Checkout: <?= $pageVars["gitCheckoutResult"] ; ?>

Git Project Deletion: <?= $pageVars["gitDeletorResult"] ; ?>


Project Initialize: <?= $pageVars["projectInitResult"] ; ?>

Project Build Install: <?= $pageVars["projectBuildResult"] ; ?>


Host File Entry Creation: <?= $pageVars["hostEditorAdditionResult"] ; ?>

Host File Entry Deletion: <?= $pageVars["hostEditorDeletionResult"] ; ?>


Virtual Host Creation: <?= $pageVars["virtualHostCreatorResult"]  ; ?>

Virtual Host Deletion: <?= $pageVars["virtualHostDeletionResult"] ; ?>


Database Reset: <?= $pageVars["dbResetResult"]  ; ?>

Database Configure: <?= $pageVars["dbConfigureResult"]  ; ?>

Database Install: <?= $pageVars["dbInstallResult"]  ; ?>

Database Drop: <?= $pageVars["dbDropResult"] ; ?>


Cuke Configure: <?= $pageVars["cukeConfAdditionResult"]  ; ?>

Cuke Reset: <?= $pageVars["cukeConfDeletionResult"]  ; ?>

<?php
} else {
?>
Auto Pilot errors:
<?= $pageVars["autoPilotErrors"]; ?>
<?php

} else if ($pageVars["route"]["action"]=="cli") {
?>
Installer Cli
<?php
} ?>

------------------------------
Installer Finished