<?php

if ($pageVars["route"]["action"]=="file") {
    ?>Download: <?php echo ($pageVars["result"] == true) ? "Success" : "Failure" ; } ?>