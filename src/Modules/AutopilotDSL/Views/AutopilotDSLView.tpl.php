AutopilotDSL Execution - <?php echo $pageVars["package-friendly"] ; ?> Installer:
--------------------------------------------
<?php

foreach ($pageVars["result"] as $key => $value) {
    ?>
Step <?php echo $key ; ?> : <?php echo $value["params"]["route"]["control"] ; ?>, <?php echo $value["params"]["route"]["action"] ; ?> : <?php

//    var_dump($value["out"]) ;

if ($value["status"]==true) {
    $stepSuccessString = "Success" ; }
else {
    $stepSuccessString = "Failure" ;
    $has_failure = true ; }

//    echo $stepSuccessString."\n" ;
    echo $value["out"]."\n" ;

}


if (isset($has_failure) && $has_failure==true) { ?>

Execution Failed

<?php
}

else { ?>

Execution Successful

<?php
}

?>
------------------------------
Installer Finished