<?php

echo "Generating Yaml for executing Behat".PHP_EOL ;

// find and collate feature dirs
define("DS", DIRECTORY_SEPARATOR) ;

$findSource = dirname(dirname(dirname(__DIR__))).DS."src".DS."Modules".DS ;
$comm = "find ".$findSource." -path '*/Tests/behat/features' -type d" ;
echo $comm."\n" ;
$behat_dirs = exec($comm, $output) ;
$feature_paths[] = "%paths.base%/features" ;
$feature_paths[] = __DIR__."/features" ;
$feature_paths = array_merge($feature_paths, $output) ;
$feature_path_string = implode(",", $feature_paths) ;

$findSource = dirname(dirname(dirname(__DIR__))).DS."src".DS."Modules".DS ;
$comm2 = "find ".$findSource." -path '*/Tests/behat/bootstrap' -type d" ;
echo $comm2."\n" ;
$bootstrap_dirs = exec($comm2, $output2) ;
$bootstrap_paths[] = "%paths.base%/bootstrap" ;
$bootstrap_paths[] = __DIR__."/bootstrap" ;
$bootstrap_paths = array_merge($bootstrap_paths, $output2) ;
$bootstrap_path_string = implode(",", $bootstrap_paths) ;

// find and collate bootstrap dirs, to scan files for Context Classes
$contexts[] = "FeatureContext" ;
$contexts[] = "AnyModuleActionsContext" ;
foreach ($output2 as $file_dir) {
    $files = scandir ($file_dir) ; {
        foreach ($files as $file) {
            if (!in_array($file, array(".", ".."))) {
                $contexts[] = str_replace(".php", "", basename($file)) ; } } } }
$contexts_string = implode(",", $contexts) ;


//var_dump("fps", $feature_path_string, "bps", $bootstrap_path_string, "cs", $contexts_string) ;

    $start_yaml = "
default:
    autoload:
        '' : {$bootstrap_path_string}
    suites:
        core_features:
            paths: [ {$feature_path_string} ]
            contexts: [ {$contexts_string} ]
" ;

$res = file_put_contents(__DIR__.DS."behat_gen.yml", $start_yaml) ;
if ($res == false) {
    echo "Unable to write Behat Configuration file to ".__DIR__.DS."behat_gen.yml".PHP_EOL ;
    exit(1) ; }
echo "Wrote {$res} Bytes in Behat Configuration file to ".__DIR__.DS."behat_gen.yml".PHP_EOL ;
exit(0) ;
