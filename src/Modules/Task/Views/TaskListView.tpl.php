<?php

foreach ($pageVars["result"] as $taskType => $details) {

    if ($taskType=="taskfile") {
        echo "Tasks from $taskType:\n" ;
        foreach ($details as $task => $taskDetails) {
            echo "  Task: $task\n" ;
            echo "    Details:\n" ;
            foreach ($taskDetails as $taskStepVal) {
                $keys = array_keys($taskStepVal) ;
                echo "      [$keys[0]] => " ;
                if (is_string($taskStepVal[$keys[0]])) { echo "[{$taskStepVal[$keys[0]]}] \n" ; }
                if (is_array($taskStepVal[$keys[0]])) {
                    $strs = array() ;
                    foreach ($taskStepVal[$keys[0]] as $paramKey => $paramVal) {
                        $strs[] = "[{$paramKey}] => [{$paramVal}]" ; }
                    $str = "[".implode(", ", $strs)."]" ;
                    echo $str." \n" ; } } }
        echo "\n" ; }

    else if ($taskType=="modules") {
        echo "Tasks from $taskType:\n" ;
        foreach ($details as $module => $tasks) {
            echo "  Module: $module\n" ;
            foreach ($tasks as $task => $taskDetails) {
                echo "    Task: $task\n" ;
                echo "      Details:\n" ;
                foreach ($taskDetails as $taskStepVal) {
                    $keys = array_keys($taskStepVal) ;
                    echo "      [$keys[0]] => " ;
                    if (is_string($taskStepVal[$keys[0]])) { echo "[{$taskStepVal[$keys[0]]}] \n" ; }
                    if (is_array($taskStepVal[$keys[0]])) {
                        $strs = array() ;
                        foreach ($taskStepVal[$keys[0]] as $paramKey => $paramVal) {
                            $strs[] = "[{$paramKey}] => [{$paramVal}]" ; }
                        $str = "[".implode(", ", $strs)."]" ;
                        echo $str." \n" ; } } } }
    }

}

?>

------------------------------
Task Finished