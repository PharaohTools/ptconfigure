<?php

if ($pageVars["route"]["action"]=="data") {
?>

SFTP SSH Data Result: <?php echo $pageVars["shlResult"] ; ?>

<?php

} else if ($pageVars["route"]["action"]=="cli") {
    ?>
Shell Result: <?php echo $pageVars["shlResult"]  ; ?>

SFTP Shell Cli
<?php
} else if ($pageVars["route"]["action"]=="script") {
    ?>
Shell Result: <?php echo $pageVars["shlResult"]  ; ?>

SFTP Script
<?php
} ?>

------------------------------
Installer Finished