<?php

if (is_object($pageVars["awsResult"]) || is_array($pageVars["awsResult"])) {
    $arrayObject = new \ArrayObject($pageVars["awsResult"]);
    foreach ($arrayObject as $arrayObjectKey => $arrayObjectValue) {
        var_dump($arrayObjectKey);
        if ($arrayObjectKey == "sizes") {
            $outVar = "" ;
            echo $arrayObjectKey.":\n";
            foreach($arrayObjectValue as $sizeEntry) {
                foreach($sizeEntry as $sizeEntryKey => $sizeEntryValue) {
                    $outVar .= $sizeEntryKey.' - '.$sizeEntryValue ; }
                $outVar .= "\n" ; }
            echo $outVar."\n" ; }
        else {
            ob_start();
            var_dump($arrayObjectValue);
            $val = ob_get_clean();
            echo $arrayObjectKey.": ".$val."\n";
        } } }
else {
    echo "Completed.";
    // echo "There was a problem.";
}

?>

------------------------------
AWS EC2 API Finished