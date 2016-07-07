Autopilot Execution - <?php echo $pageVars["package-friendly"] ; ?> Installer:
--------------------------------------------
<?php

if (isset($pageVars["result"]) && is_array($pageVars["result"])) {
    foreach ($pageVars["result"] as $key => $value) {
        ?>Step <?php echo $key ; ?> : <?php echo $value["params"]["route"]["control"] ; ?>, <?php echo $value["params"]["route"]["action"] ; ?> : <?php


if ($value["status"]==true) {
    $stepSuccessString = "Success" ; }
else {
    $stepSuccessString = "Failure" ;
    $has_failure = true ; }

//    echo $stepSuccessString."\n" ;
    echo $value["out"]."\n" ;
    }
}
else {
    echo "No steps have been executed" ;    }


if ( (isset($has_failure) && $has_failure==true) || \Core\BootStrap::getExitCode() !== 0 ) { ?>

Execution Failed

<?php
}

else { ?>

Execution Successful

<?php
}

?>
------------------------------
Automation Run Finished