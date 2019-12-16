<?php

if ($pageVars["route"]["action"]=="file") {
    ?>Upload: <?php echo ($pageVars["result"] == true) ? "Success" : "Failure" ; } ?>