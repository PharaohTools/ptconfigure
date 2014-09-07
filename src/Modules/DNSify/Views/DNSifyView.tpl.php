<?php

if ($pageVars["route"]["action"]=="ensure-domain-exists") {
    if (is_object($pageVars["result"])) {
        if ($pageVars["result"]->status == "already-exists") {
            echo "Requested domain {$pageVars["result"]->requested} found with id {$pageVars["result"]->domain_id}." ; }
        else {
            echo "Requested domain {$pageVars["result"]->requested} not found, so was created with id {$pageVars["result"]->domain_id}." ; } }
    else {
        echo "No Object."; } }
else if ($pageVars["route"]["action"]=="ensure-record-exists") {
    if (is_object($pageVars["result"])) {
        if ($pageVars["result"]->status == "already-exists") {
            echo "Requested domain record {$pageVars["result"]->requested_type} {$pageVars["result"]->requested_name} {$pageVars["result"]->requested_data} found with id {$pageVars["result"]->record->id}" ; }
        else {
            echo "Requested domain record {$pageVars["result"]->requested_type} {$pageVars["result"]->requested_name} {$pageVars["result"]->requested_data} not found, so was created." ; } }
    else {
        echo "No Object."; } }
else if ($pageVars["route"]["action"]=="list-domains") {
    if (is_array($pageVars["result"])) {
        foreach ($pageVars["result"] as $domain) {
            echo "\n" ;
            echo "Name: ".$domain->name."\n";
            echo "ID: ".$domain->id."\n"; } } }
else if ($pageVars["route"]["action"]=="list-records") {
    if (is_array($pageVars["result"])) {
        foreach ($pageVars["result"] as $domainName => $domainOfRecords) {
            echo "Domain: $domainName\n" ;
            foreach ($domainOfRecords as $domainRecord) {
                echo "  Record:\n" ;
                echo "    Type: {$domainRecord->type}\n" ;
                echo "    Name: {$domainRecord->name}\n" ;
                echo "    ID: {$domainRecord->id}\n" ;
                echo "    TTL: {$domainRecord->ttl}\n" ;
                echo "    Data: {$domainRecord->data}\n" ; } } } }

?>

------------------------------
DNSifying Finished