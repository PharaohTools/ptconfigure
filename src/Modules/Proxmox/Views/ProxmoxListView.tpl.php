<?php
if (is_object($pageVars["result"]) || is_array($pageVars["result"])) {
    $arrayObject = new \ArrayObject($pageVars["result"]);
    foreach ($arrayObject as $arrayObjectKey => $arrayObjectValue) {
        $outVar = "" ;
        if ($arrayObjectKey == "status") {
            echo $arrayObjectKey.": $arrayObjectValue\n";  }
        else if ($arrayObjectKey == "droplets") {
            foreach($arrayObjectValue as $dropletEntry) {
                $outVar .= "id: ".$dropletEntry->id.", ";
                $outVar .= "name: ".$dropletEntry->name.", ";
                $outVar .= "image_id: ".$dropletEntry->image->id.", ";
                $outVar .= "size_id: ".$dropletEntry->size_slug.", ";
                $outVar .= "region_id: ".$dropletEntry->region->slug.", ";
                $ba = (isset($dropletEntry->backups_ids) && count($dropletEntry->backups_ids)>0) ?  "Yes" : "No" ;
                $outVar .= "backups_active: ".$ba.", ";

                foreach ($dropletEntry->networks->v4 as $v4ip) {
                    if ($v4ip->type == "public") {
                        $outVar .= "public_ip_address: ".$v4ip->ip_address.", "; } }

                foreach ($dropletEntry->networks->v4 as $v4ip) {
                    if ($v4ip->type == "private") {
                        $outVar .= "private_ip_address: ".$v4ip->ip_address.", "; } }

                $lck = ($dropletEntry->locked ==true) ?  "Yes" : "No" ;
                $outVar .= "locked: ".$lck.", ";
                $outVar .= "status: ".$dropletEntry->status.", ";
                $outVar .= "created_at: ".$dropletEntry->created_at;
                $outVar .= "\n\n" ; } }
        else if ($arrayObjectKey == "sizes") {
            foreach($arrayObjectValue as $sizeEntry) {
                $outVar .= "slug: ".$sizeEntry->slug.", ";
                $outVar .= "memory: ".$sizeEntry->memory.", ";
                $outVar .= "vcpus: ".$sizeEntry->vcpus.", ";
                $outVar .= "disk: ".$sizeEntry->disk.", ";
                $outVar .= "transfer: ".$sizeEntry->disk.", ";
                $outVar .= "price_monthly: ".$sizeEntry->price_monthly.", ";
                $outVar .= "price_hourly: ".$sizeEntry->price_hourly.", ";
                $outVar .= "regions: ".implode(",", $sizeEntry->regions);
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "images") {
            foreach($arrayObjectValue as $imageEntry) {
                $outVar .= "id: ".$imageEntry->id.", ";
                $outVar .= "name: ".$imageEntry->name.", ";
                $outVar .= "distribution: ".$imageEntry->distribution.", ";
                $outVar .= "slug: ".$imageEntry->slug.", ";
                $outVar .= "public: ".$imageEntry->public ;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "domains") {
            foreach($arrayObjectValue as $domainEntry) {
                $outVar .= "id: ".$domainEntry->id.", ";
                $outVar .= "name: ".$domainEntry->name.", ";
                $outVar .= "ttl: ".$domainEntry->ttl.", ";
                $outVar .= "live_zone_file: ".$domainEntry->live_zone_file.", ";
                $outVar .= "zone_file_with_error: ".$domainEntry->zone_file_with_error ;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "regions") {
            foreach($arrayObjectValue as $regionEntry) {
                $outVar .= "name: ".$regionEntry->name.", ";
                $outVar .= "slug: ".$regionEntry->slug.", ";
                $outVar .= "features: ".implode(",", $regionEntry->features)." , ";
                $outVar .= "sizes: ".implode(",", $regionEntry->sizes);
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "ssh_keys") {
            foreach($arrayObjectValue as $sshKeyEntry) {
                $outVar .= "name: ".$sshKeyEntry->name.", ";
                $outVar .= "id: ".$sshKeyEntry->id.", ";
                $outVar .= "fingerprint: ".$sshKeyEntry->fingerprint ;
                $outVar .= "\n" ; } }
        else if ($arrayObjectKey == "load_balancers") {
            // var_dump($arrayObjectValue) ;
            foreach($arrayObjectValue as $loadBalancerEntry) {
                $outVar .= "name: ".$loadBalancerEntry->name."\n";
                $outVar .= "id: ".$loadBalancerEntry->id."\n";
                $outVar .= "ip: ".$loadBalancerEntry->ip."\n";
                $outVar .= "status: ".$loadBalancerEntry->status."\n";
                $outVar .= "algorithm: ".$loadBalancerEntry->algorithm."\n";
                $outVar .= "sticky_sessions: ".$loadBalancerEntry->sticky_sessions->type."\n";
                $outVar .= "created_at: ".$loadBalancerEntry->created_at."\n";
                $outVar .= "droplet_ids: \n" ;
                foreach ($loadBalancerEntry->droplet_ids as $droplet_id) {
                    $outVar .= "  ".$droplet_id."\n" ; }
                $outVar .= "health_check: \n" ;
                $outVar .= "  protocol: ".$loadBalancerEntry->health_check->protocol."\n" ;
                $outVar .= "  port: ".$loadBalancerEntry->health_check->port."\n" ;
                $outVar .= "  path: ".$loadBalancerEntry->health_check->path."\n" ;
                $outVar .= "  check_interval_seconds: ".$loadBalancerEntry->health_check->check_interval_seconds."\n" ;
                $outVar .= "  response_timeout_seconds: ".$loadBalancerEntry->health_check->response_timeout_seconds."\n" ;
                $outVar .= "  healthy_threshold: ".$loadBalancerEntry->health_check->healthy_threshold."\n" ;
                $outVar .= "  unhealthy_threshold: ".$loadBalancerEntry->health_check->unhealthy_threshold."\n" ;
                $outVar .= "forwarding_rules: \n" ;
                $i = 1 ;
                foreach ($loadBalancerEntry->forwarding_rules as $forwarding_rule) {
                    $outVar .= "  {$i}: " ;
                    $outVar .= " entry_protocol: ".$forwarding_rule->entry_protocol."," ;
                    $outVar .= " entry_port: ".$forwarding_rule->entry_port."," ;
                    $outVar .= " target_protocol: ".$forwarding_rule->target_protocol."," ;
                    $outVar .= " target_port: ".$forwarding_rule->target_port."," ;
                    $outVar .= " certificate_id: ".$forwarding_rule->certificate_id."," ;
                    $outVar .= " tls_passthrough: ".$forwarding_rule->tls_passthrough."\n" ;
                    $i ++ ; }

                $outVar .= "\n" ; } }
        echo $arrayObjectKey.":\n\n";
        echo $outVar."\n" ; } }
else {
    echo "There was a problem listing Data. No results were found"; }
?>