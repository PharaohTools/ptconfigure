<?php

function taskViewEchoLines($line) {
    foreach ($line as $linepart) {
        if (is_array($linepart)) { taskViewEchoLines($linepart); }
        else { echo "$linepart\n" ; } }
}

if (is_array($pageVars["result"])) {
    foreach($pageVars["result"] as $line) {
        if (is_array($line)) { taskViewEchoLines($line) ; }
        else { echo "$line\n" ; } } }
else {
    echo "Unreadable output\n" ; }
?>