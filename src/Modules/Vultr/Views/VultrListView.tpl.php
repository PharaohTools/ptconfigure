<?php
//var_dump( $pageVars);
if (is_object($pageVars["result"]) || is_array($pageVars["result"])) {
    $arrayObject = new \ArrayObject($pageVars["result"]);

   // foreach ($arrayObject as $arrayObjectKey => $arrayObjectValue) {
        $outVar = "" ;
//        if ($arrayObjectKey == "status") {
//            echo $arrayObjectKey.": $arrayObjectValue\n";  }
//        else
        if ($pageVars["params"]["type"] == "servers") {
            foreach($arrayObject as $serverEntry) {
                $outVar .= "id: ".$serverEntry->SUBID.", ";
                $outVar .= "name: ".$serverEntry->label.", ";
                $outVar .= "image_id: ".$serverEntry->os.", ";
                $outVar .= "size_id: ".$serverEntry->VPSPLANID.", ";
                $outVar .= "region_id: ".$serverEntry->location.", ";
                $ba = (isset($serverEntry->backups_ids) && count($serverEntry->backups_ids)>0) ?  "Yes" : "No" ;
                $outVar .= "backups_active: ".$ba.", ";

                if (isset($serverEntry->main_ip)) {
                    $outVar .= "public_ip_address: ".$serverEntry->main_ip.", "; }

                if (isset($serverEntry->internal_ip)) {
                    $outVar .= "private_ip_address: ".$serverEntry->internal_ip.", "; }

                $outVar .= "status: ".$serverEntry->status.", ";
                $outVar .= "power_status: ".$serverEntry->power_status.", ";
                $outVar .= "created_at: ".$serverEntry->date_created;
                $outVar .= "\n\n" ; } }
        else if ($pageVars["params"]["type"] == "sizes") {
            foreach($arrayObject as $sizeEntry) {
                $outVar .= "slug: ".$sizeEntry->name.", ";
                $outVar .= "memory: ".$sizeEntry->ram.", ";
                $outVar .= "vcpus: ".$sizeEntry->vcpu_count.", ";
                $outVar .= "disk: ".$sizeEntry->disk.", ";
                $outVar .= "transfer: ".$sizeEntry->bandwidth.", ";
                $outVar .= "price_monthly: ".$sizeEntry->price_per_month.", ";
//                $outVar .= "price_hourly: ".$sizeEntry->price_hourly.", ";
                $outVar .= "regions: ".implode(",", $sizeEntry->available_locations);
                $outVar .= "\n" ; } }
        else if ($pageVars["params"]["type"] == "images") {
            foreach($arrayObject as $imageEntry) {
                $outVar .= "id: ".$imageEntry->id.", ";
                $outVar .= "name: ".$imageEntry->name.", ";
                $outVar .= "distribution: ".$imageEntry->distribution.", ";
                $outVar .= "slug: ".$imageEntry->slug.", ";
                $outVar .= "public: ".$imageEntry->public ;
                $outVar .= "\n" ; } }
        else if ($pageVars["params"]["type"] == "os") {
            foreach($arrayObject as $osEntry) {
                $outVar .= "id: ".$osEntry->OSID.", ";
                $outVar .= "name: ".$osEntry->name.", ";
                $outVar .= "arch: ".$osEntry->arch.", ";
                $outVar .= "family: ".$osEntry->family.", ";
                $outVar .= "windows: ". (($osEntry->windows==true) ? "Yes" : "No") .", ";
                $outVar .= "\n" ; } }
        else if ($pageVars["params"]["type"] == "domains") {
            foreach($arrayObject as $domainEntry) {
                $outVar .= "id: ".$domainEntry->id.", ";
                $outVar .= "name: ".$domainEntry->name.", ";
                $outVar .= "ttl: ".$domainEntry->ttl.", ";
                $outVar .= "live_zone_file: ".$domainEntry->live_zone_file.", ";
                $outVar .= "zone_file_with_error: ".$domainEntry->zone_file_with_error ;
                $outVar .= "\n" ; } }
        else if ($pageVars["params"]["type"] == "regions") {
            foreach($arrayObjectValue as $regionEntry) {
                $outVar .= "name: ".$regionEntry->name.", ";
                $outVar .= "slug: ".$regionEntry->slug.", ";
                $outVar .= "features: ".implode(",", $regionEntry->features)." , ";
                $outVar .= "sizes: ".implode(",", $regionEntry->sizes);
                $outVar .= "\n" ; } }
        else if ($pageVars["params"]["type"] == "ssh_keys") {
            foreach($arrayObject as $sshKeyEntry) {
                $outVar .= "name: ".$sshKeyEntry->name.", ";
                $outVar .= "id: ".$sshKeyEntry->id.", ";
                $outVar .= "fingerprint: ".$sshKeyEntry->fingerprint ;
                $outVar .= "\n" ; } }
        echo $pageVars["params"]["type"].":\n\n";
        echo $outVar."\n" ; }
//}
else {
    echo "There was a problem listing Data. No results were found"; }
?>
