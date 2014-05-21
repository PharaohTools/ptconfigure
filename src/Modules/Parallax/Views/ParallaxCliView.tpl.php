<?php echo $pageVars["cliResult"][0] ; ?>

-----------------

In Cli

<?php

if ($pageVars["cliResult"][1] == true) {
  echo "At least one parallel command exited with 1 status (FAILED), so I am too...\n";
  exit(1); }

?>
