<?php

if ($pageVars["route"]["action"]=="put") {
?>

SFTP Put: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

<?php

} else if ($pageVars["route"]["action"]=="get") {
    ?>
Shell Result: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

SFTP Get
<?php
} ?>

------------------------------
Installer Finished