<?php

if ($pageVars["route"]["action"]=="data") {
?>

Invoke SSH Data Result: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

<?php

} else if ($pageVars["route"]["action"]=="cli") {
    ?>
Shell Result: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

Invoke Shell Cli
<?php
} else if ($pageVars["route"]["action"]=="script") {
    ?>
Shell Result: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

Invoke Script
<?php
} ?>

------------------------------
Installer Finished