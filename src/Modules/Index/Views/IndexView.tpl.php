

Cleopatra by Golden Contact Computing
-------------------

About:
-----------------
This tool helps with setting up boxes. Its intended to get any box in your standard main environments to be
up and running quickly. It's not meant to be an exhaustive list of installs for everything you'll ever need to
install (obviously)

Can be used to set up a Development Client, Development Server, Testing Server, or Production Server in minutes

... Staging/UAT is not missing from the list because in "Software my box has installed" terms it should be the
same as Production.

Furthermore, especially for Production, this is intended to be a quick solution to get you up and running and I
do not and would never recommend going into Production without checking things for yourself.

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