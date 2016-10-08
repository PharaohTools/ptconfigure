<?php

if ($pageVars["route"]["action"]=="data") {
?>Data: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?><?php

} else if ($pageVars["route"]["action"]=="cli") {
    ?>
Shell Result: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

LetsEncrypt Shell Cli
<?php
} else if ($pageVars["route"]["action"]=="script") {
    ?>Script: <?php echo ($pageVars["shlResult"] == true) ? "Success" : "Failure" ; ?>

LetsEncrypt Script
<?php
} ?>