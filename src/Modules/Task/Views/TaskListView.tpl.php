<?php

foreach ($pageVars["result"] as $taskType => $details) {
    echo "Tasks from $taskType:\n" ;
    foreach ($details as $task => $taskDetails) {
        echo "  Task: $task\n" ;
        echo "    Details:\n" ;
        foreach ($taskDetails as $taskStepKey => $taskStepVal) {
            echo "      [$taskStepKey] => [$taskStepVal] \n" ; } }
    echo "\n" ; }
?>

------------------------------
Task Finished