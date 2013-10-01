<?php

$arrayObject = new \ArrayObject($pageVars["digiOceanResult"]);
foreach ($arrayObject as $arrayObjectKey => $arrayObjectValue) {
   echo $arrayObjectKey.": ".$arrayObjectValue."\n";
}

?>

------------------------------
Installer Finished