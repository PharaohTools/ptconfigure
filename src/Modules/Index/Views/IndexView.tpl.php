Dapperstrano by Golden Contact Computing
-------------------

About:
-----------------
This tool helps with setting up projects. It's really cool for cloning/installing/spinning up webs apps easily and
quickly.

Very cool for CI, after your CI tool performs the project checkout to run tests, you can install your webb app in one
line like:

dapperstrano install autopilot *autopilot-file*


Installation
-----------------

To install dapperstrano cli on your machine do the following. If you already have php5 and git installed skip line 1:

line 1: apt-get php5 git
line 2: git clone https://github.com/phpengine/dapperstrano && sudo dapperstrano/install

... that's it, now the dapperstrano command should be available at the command line for you.

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