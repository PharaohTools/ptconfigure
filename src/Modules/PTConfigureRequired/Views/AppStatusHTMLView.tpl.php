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

  $result_summary = (strlen($pageVars["appStatusResult"])>0) ? "Installed" : "Not Installed" ;
  echo $result_summary."\n\n" ;
  $lines = explode(PHP_EOL, $pageVars["appStatusResult"]);
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