<html>

<head>
  <title>
    GC Cleopatra
  </title>
</head>

<body>

  <h3>
    Systems Detection:<br />
    --------------------------------------------
  </h3>

  <p>
  <?php
    echo "Operating System: " . $pageVars["result"]["os"] . "\n" ;
    echo "Linux Type: " . $pageVars["result"]["linuxType"] . "\n" ;
    echo "Distro: " . $pageVars["result"]["distro"] . "\n" ;
    echo "Version: " . $pageVars["result"]["version"] . "\n" ;
    echo "Architecture: " . $pageVars["result"]["architecture"] . "\n" ;
    echo "Host Name: " . $pageVars["result"]["hostName"] . "\n" ;
  ?>
  </p>

  <p>
    ------------------------------<br />
      Detection Finished
  </p>


</body>

</html>