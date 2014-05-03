Boxify Listing:
--------------------------------------------

<?php echo $pageVars["appName"] ; ?>: <?php
    $result_summary = ($pageVars["result"] == true) ? "Success" : "Failure" ;
    echo $result_summary."\n" ;
    if (isset($pageVars["result"])) {
        $i = 1;
        foreach ($pageVars["result"] as $result) {
            echo "\n";
            echo "Environment: $i\n";
            echo "  ".$result["any-app"]["gen_env_name"]."\n";
            $i2 = 0 ;
            echo "Servers:\n";
            foreach ($result["servers"] as $server) {
                echo "  Server $i2:\n" ;
                foreach ($server as $serverKey => $serverVal) {
                    echo "    $serverKey : $serverVal\n"; }
                $i2++; }
            $i++; } }
    else {
        echo "No Results\n" ; }
?>

------------------------------
Boxify Listing Finished