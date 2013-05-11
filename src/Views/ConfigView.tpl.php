<?php

if ($pageVars["route"]["action"] == "list") {
    if (count($pageVars["configResult"])>0) {
        foreach ($pageVars["configResult"] as $configResultKey => $configResultValues) {
            echo "Variable Name is: ". $configResultKey." \n";
            if ( is_array($configResultValues) ) {
                for ($i=0 ; $i<count($configResultValues); $i++) {
                    echo "Multiple Value $i is: $configResultValues \n";  } }
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