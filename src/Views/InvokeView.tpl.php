<?php

if ($pageVars["route"]["action"]=="autopilot") {
  if (!isset($pageVars["autoPilotErrors"])) {
?>

Invoke SSH Script Result: <?= $pageVars["invSshScriptResult"] ; ?>

Invoke SSH Data Result: <?= $pageVars["invSshDataResult"] ; ?>

<?php
} else {
?>
Auto Pilot errors:
<?= $pageVars["autoPilotErrors"]; ?>
<?php
} } else if ($pageVars["route"]["action"]=="shell") {
    ?>
Shell Result: <?= $pageVars["shlResult"]  ; ?>

Invoke Shell Cli
<?php
} else if ($pageVars["route"]["action"]=="script") {
    ?>
Shell Result: <?= $pageVars["shlResult"]  ; ?>

Invoke Script
<?php
} ?>

------------------------------
Installer Finished