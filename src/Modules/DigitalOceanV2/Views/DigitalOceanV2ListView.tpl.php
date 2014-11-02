<?php
if (is_object($pageVars["digiOceanV2Result"]) || is_array($pageVars["digiOceanV2Result"])) {
    $arrayObject = new \ArrayObject($pageVars["digiOceanV2Result"]);
    foreach ($arrayObject as $arrayObjectKey => $arrayObjectValue) {
        $outVar = "" ;
        if ($arrayObjectKey == "status") {
            echo $arrayObjectKey.": $arrayObjectValue\n";  }
        else if ($arrayObjectKey == "droplets") {
            foreach($arrayObjectValue as $dropletEntry) {
                $outVar .= "id - ".$dropletEntry->id.", ";
                $outVar .= "name - ".$dropletEntry->name.", ";
                $outVar .= "image_id - ".$dropletEntry->image_id.", ";
                $outVar .= "size_id - ".$dropletEntry->size_id.", ";
                $outVar .= "region_id - ".$dropletEntry->region_id.", ";
                $outVar .= "backups_active - ".$dropletEntry->backups_active.", ";
                $outVar .= "ip_address - ".$dropletEntry->ip_address.", ";
                $outVar .= "private_ip_address - ".$dropletEntry->private_ip_address.", ";
                $outVar .= "locked - ".$dropletEntry->locked.", ";
                $outVar .= "status - ".$dropletEntry->status.", ";
                $outVar .= "created_at - ".$dropletEntry->created_at;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "sizes") {
            foreach($arrayObjectValue as $sizeEntry) {
                $outVar .= "id - ".$sizeEntry->id.", ";
                $outVar .= "name - ".$sizeEntry->name.", ";
                $outVar .= "slug - ".$sizeEntry->slug.", ";
                $outVar .= "memory - ".$sizeEntry->memory.", ";
                $outVar .= "cpu - ".$sizeEntry->cpu.", ";
                $outVar .= "disk - ".$sizeEntry->disk.", ";
                $outVar .= "cost_per_hour - ".$sizeEntry->cost_per_hour.", ";
                $outVar .= "cost_per_month - ".$sizeEntry->cost_per_month;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "images") {
            foreach($arrayObjectValue as $imageEntry) {
                $outVar .= "id - ".$imageEntry->id.", ";
                $outVar .= "name - ".$imageEntry->name.", ";
                $outVar .= "distribution - ".$imageEntry->distribution.", ";
                $outVar .= "slug - ".$imageEntry->slug.", ";
                $outVar .= "public - ".$imageEntry->public ;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "domains") {
            foreach($arrayObjectValue as $domainEntry) {
                $outVar .= "id - ".$domainEntry->id.", ";
                $outVar .= "name - ".$domainEntry->name.", ";
                $outVar .= "ttl - ".$domainEntry->ttl.", ";
                $outVar .= "live_zone_file - ".$domainEntry->live_zone_file.", ";
                $outVar .= "zone_file_with_error - ".$domainEntry->zone_file_with_error ;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "regions") {
            foreach($arrayObjectValue as $regionEntry) {
                $outVar .= "id - ".$regionEntry->id.", ";
                $outVar .= "name - ".$regionEntry->name.", ";
                $outVar .= "slug - ".$regionEntry->slug;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "ssh_keys") {
            foreach($arrayObjectValue as $sshKeyEntry) {
                $outVar .= "id - ".$sshKeyEntry->id.", ";
                $outVar .= "name - ".$sshKeyEntry->name ;
                $outVar .= "\n" ; } }
        echo $arrayObjectKey.":\n";
        echo $outVar."\n" ; } }
else {
    echo "There was a problem listing Data. No results were found"; }
?>

------------------------------
Digital Ocean Listing Finished