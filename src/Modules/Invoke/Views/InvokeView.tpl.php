<?php

if ($pageVars["route"]["action"]=="autopilot") {
  if (!isset($pageVars["autoPilotErrors"])) {
?>

Invoke SSH Script Result: <?php echo $pageVars["sshInvokeScriptResult"] ; ?>

Invoke SSH Data Result: <?php echo $pageVars["sshInvokeDataResult"] ; ?>

<?php
} else {
?>
Auto Pilot errors:
<?php echo $pageVars["autoPilotErrors"]; ?>
<?php
} } else if ($pageVars["route"]["action"]=="shell") {
    ?>
Shell Result: <?php echo $pageVars["shlResult"]  ; ?>

Invoke Shell Cli
<?php
} else if ($pageVars["route"]["action"]=="script") {
    ?>
Shell Result: <?php echo $pageVars["shlResult"]  ; ?>

Invoke Script
<?php
} ?>

------------------------------
Installer Finished