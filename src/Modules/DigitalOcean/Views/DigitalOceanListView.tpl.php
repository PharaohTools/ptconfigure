<?php

if (is_object($pageVars["digiOceanResult"]) || is_array($pageVars["digiOceanResult"])) {
    $arrayObject = new \ArrayObject($pageVars["digiOceanResult"]);
    foreach ($arrayObject as $arrayObjectKey => $arrayObjectValue) {
        ob_start();
        var_dump($arrayObjectValue);
        $val = ob_get_clean();
        echo $arrayObjectKey.": ".$val."\n"; } }

else {
    echo "There was a problem listing Data"; }

?>

------------------------------
Digital Ocean Listing Finished