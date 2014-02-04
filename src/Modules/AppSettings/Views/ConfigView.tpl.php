<?php

if ($pageVars["route"]["action"] == "list") {
    if (count($pageVars["configResult"])>0) {
        foreach ($pageVars["configResult"] as $configResultKey => $configResultValues) {
            echo "Variable Type is: ". $configResultKey." \n";
            if ( is_array($configResultValues) || is_object($configResultValues) ) {
              //for ($i=0 ; $i<count($configResultValues); $i++) {
              $i = 0;
              foreach ($configResultValues as $configResultKey => $configResultValue ) {
                echo "   $configResultKey";
                if (isset($configResultValue) && strlen($configResultValue)>0) { echo ' is: '.$configResultValue ; }
                echo " \n";
                $i++; } }
            else {
                    echo "Single Value is: $configResultValues \n"; } } }
    else {
        echo "No Variables Configured"; } }
# else if ($pageVars["route"]["action"] == "set") {
else {
   echo $pageVars["configResult"] ;
}

?>

In Application Config