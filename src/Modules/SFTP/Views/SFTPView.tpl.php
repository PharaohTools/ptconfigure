<?php

if ($pageVars["route"]["action"]=="put") {
?>SFTP Put: <?php echo ($pageVars["result"] == true) ? "Success" : "Failure" ; ?>

<?php

} else if ($pageVars["route"]["action"]=="get") {
    ?>SFTP Get: <?php echo ($pageVars["result"] == true) ? "Success" : "Failure" ; ?>
<?php
} ?>