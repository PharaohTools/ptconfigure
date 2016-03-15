<html>

<head>
  <title>
    GC PTConfigure
  </title>
</head>

<body>

  <h3>
    Single Step<br />
    --------------------------------------------
  </h3>

  <?php echo $pageVars["appName"] ; ?>: <?php

  $result_summary = (strlen($pageVars["result"])>0) ? "Success" : "Failure" ;
  echo $result_summary."\n\n" ;
  $lines = explode(PHP_EOL, $pageVars["result"]);
  foreach ($lines as $line) {
     echo "<p>$line</p>";
  }

  ?>

  <p>
    ------------------------------<br />
    Step Complete
  </p>


</body>

</html>