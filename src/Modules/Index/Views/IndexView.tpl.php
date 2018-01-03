<?php
if ($pageVars["quiet"] == false) {

?>

Pharaoh Deploy
-------------------

About:
-----------------
Automated Deployment, Web App Versioning and Infrastructure by Code in PHP. PTDeploy deploys PHP Applications in a
really simple way, and does it all through code configuration. That's what it's about.

This tool is for provisioning applications and builds to your boxes. You can set up simple or complex application
deployment patterns to your systems with one or two PHP files, or quickly set up cloud friendly deployment patterns.

PTDeploy is modular. object oriented and extendible, you can pretty easily write your own module if you want
functionality we haven't yet covered. Feel free to submit us pull requests.

This is part of the Pharaoh Tools suite, which covers Configuration Management, Test Automation Management, Automated
Deployment, Build and Release Management and more, all implemented in code, and all in PHP.

Its easy to write modules for any Operating System but we've begun with Ubuntu and adding more as soon as possible.
Currently, all of the Modules work on Ubuntu 12, most on 13, and a few on Centos.

If you've heard of the Ruby tool Capistrano, then you can probably guess why this is called PTDeploy. It performs a
similar function (app deployment), but does it in PHP (Because PHP is way cooler). This tool helps just as well with
setting up projects locally or on 50 remote servers. It's really cool for cloning / installing / spinning up web
apps easily and quickly - to one or multiple servers using one or two config files. Just as Capistrano is a must for
your Ruby CI setup, PTDeploy is a must for your PHP CI.

-------------------------------------------------------------

Available Commands:
---------------------------------------

<?php

    if (isset($pageVars["modulesInfo"]) && is_array($pageVars["modulesInfo"])) {
        foreach ($pageVars["modulesInfo"] as $moduleInfo) {
            if ($moduleInfo["hidden"] != true) {
                echo $moduleInfo["command"].' - '.$moduleInfo["name"]."\n"; }
        }
    }

} else {

    echo "Pharaoh Deploy" ;
}
?>