<html>

<head>
  <title>
    GC PTConfigure
  </title>
</head>

<body>

  <h3>
    Single App Installer:<br />
    --------------------------------------------
  </h3>

  <?php echo $pageVars["appName"] ; ?>: <?php

  $result_summary = (strlen($pageVars["appInstallResult"])>0) ? "Success" : "Failure" ;
  echo $result_summary."\n\n" ;
  $lines = explode(PHP_EOL, $pageVars["appInstallResult"]);
  foreach ($lines as $line) {
     echo "<p>$line</p>";
  }

  ?>

  <p>
    ------------------------------<br />
    Installer Finished
  </p>


</body>

</html>