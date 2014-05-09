<?php

if ($pageVars["route"]["action"]=="put") {
?>

SFTP Put: <?php echo ($pageVars["result"] == true) ? "Success" : "Failure" ; ?>

<?php

} else if ($pageVars["route"]["action"]=="get") {
    ?>
Shell Result: <?php echo ($pageVars["result"] == true) ? "Success" : "Failure" ; ?>

SFTP Get
<?php
} ?>

------------------------------
Installer Finished