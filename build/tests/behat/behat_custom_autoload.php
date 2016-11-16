<?php

$default_dir = dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR."build".DIRECTORY_SEPARATOR."tests".DIRECTORY_SEPARATOR."behat".DIRECTORY_SEPARATOR."bootstrap" ;

$findSource = dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."Modules".DIRECTORY_SEPARATOR ;
$comm2 = "find ".$findSource." -path '*/Tests/behat/bootstrap' -type d" ;
echo $comm2."\n" ;
$bootstrap_dirs = exec($comm2, $output2) ;
$output2[] = $default_dir ;

foreach ($output2 as $file_dir) {
    $files = scandir ($file_dir) ; {
        foreach ($files as $file) {
            if (!in_array($file, array(".", ".."))) {
                $full_path = $file_dir.DIRECTORY_SEPARATOR.$file ;
                echo "Autoloading {$full_path}... ".PHP_EOL ;
                require_once ($full_path) ; } } } }