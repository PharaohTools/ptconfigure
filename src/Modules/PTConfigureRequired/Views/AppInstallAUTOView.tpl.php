<?php
$mod = (isset($pageVars["module"])) ? $pageVars["module"] : "No Module" ;
echo $mod ; ?>: <?php
$result_summary = (isset($pageVars["result"]) && $pageVars["result"] == true) ? "Success" : "Failure" ;
echo $result_summary."\n" ;
if (isset($pageVars["result"]) && is_string($pageVars["result"])) {
    echo $pageVars["result"]."\n" ; }
?>