Autopilot Execution Summary:
--------------------------------------------
<?php

//var_dump($pageVars) ;

if (isset($pageVars["result"]) && is_array($pageVars["result"])) {
    foreach ($pageVars["result"] as $key => $value) {
        $prc = (isset($value["params"]["route"]["control"])) ? $value["params"]["route"]["control"] : "" ;
        $pra = (isset($value["params"]["route"]["action"])) ? $value["params"]["route"]["action"] : "" ;

        ?>Step <?php echo $key ; ?> : <?php echo $prc ; ?>, <?php echo $pra ; ?> : <?php


        if ($value["status"]==true) {
            $stepSuccessString = "Success" ; }
        else {
            $stepSuccessString = "Failure" ;
            $has_failure = true ; }

        if (isset($value["out"])) {
            echo $value["out"]."\n" ;
        }
        
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