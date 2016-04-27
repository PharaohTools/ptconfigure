User Modifications:
--------------------------------------------

<?php echo $pageVars["appName"] ; ?>: <?php

    if ($pageVars["pageVars"]["route"]["action"] == "exists") {
        $result_summary = ($pageVars["result"] == true) ? "Success = User Exists" : "Failure - User Does Not Exist" ;
        $user_name = "" ;
        $user_name = (isset($pageVars["params"]["user-name"])) ? $pageVars["params"]["user-name"] : $user_name ;
        $user_name = (isset($pageVars["params"]["username"])) ? $pageVars["params"]["username"] : $user_name ;
        echo $result_summary."\n" ;
        echo "User Name: ".$user_name."\n" ; }
    else if (is_string($pageVars["result"])) {
        echo $pageVars["result"]."\n" ; }
    else {
        echo $pageVars["result"]."\n" ; }

?>

------------------------------
User Mods Finished