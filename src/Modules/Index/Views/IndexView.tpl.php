Cleopatra - Pharaoh Tools
-------------------

Configuration, Infrastructure and Systems Automation Management in PHP.

Can be used to set up a Development Client, Development Server, Testing Servers, SCM Servers or Production
Application Servers in minutes, out of the box, with Zero configuration across multiple Operating Systems.

You can quickly create simple or complex systems completely configured by code across platforms.

Using Convention over Configuration, a lot of common Configuration Management tasks can be completed with little or
no extra implementation work.

-------------------------------------------------------------

Available Commands:
---------------------------------------

<?php

foreach ($pageVars["modulesInfo"] as $moduleInfo) {
  if ($moduleInfo["hidden"] != true) {
    echo $moduleInfo["command"].' - '.$moduleInfo["name"]."\n";
  }
}

?>