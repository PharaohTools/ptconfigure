<?php

foreach ($pageVars["result"] as $taskType => $details) {
    echo "Tasks from $taskType:\n" ;
    foreach ($details as $task => $taskDetails) {
        echo "  Task: $task\n" ;
        echo "    Details:\n" ;
        foreach ($taskDetails as $taskStepVal) {
            $keys = array_keys($taskStepVal) ;
            echo "      [$keys[0]] => " ;
            if (is_string($taskStepVal[$keys[0]])) { echo "[{$taskStepVal[$keys[0]]}] \n" ; }
            // else { var_dump($taskStepVal[$keys[0]]) ; }
            if (is_array($taskStepVal[$keys[0]])) {
                $strs = array() ;
                foreach ($taskStepVal[$keys[0]] as $paramKey => $paramVal) {
                    $strs[] = "[{$paramKey}] => [{$paramVal}]" ; }
                $str = "[".implode(", ", $strs)."]" ;
                echo $str ;}
           //  else { echo '[]' ; }
        } }
    echo "\n" ; }
?>

------------------------------
Task Finished