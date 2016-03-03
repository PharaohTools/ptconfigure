 <?php


if (!isset($argv[1]) || !isset($argv[2])) {
    echo "Usage: \n" ;
    echo "php module_maker.php OldModule NewModule search:replace,search:replace,search:replace \n" ;
    echo "The array of searches and replaces is\n" ;
    exit(1) ;
}

if (isset($argv[3])) {
    $translates = loopOurTranslates($argv[3]) ;
//    var_dump($translates) ;
}

$original_module = $argv[1] ;
$new_module = $argv[2] ;
$parent_module_path = __DIR__.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."Modules".DIRECTORY_SEPARATOR ;
$original_module_path = $parent_module_path.$original_module ;
$original_module_files = array_slice(scandir($original_module_path), 2);

echo "Going to copy from {$original_module} to {$new_module}\n" ;
echo "Original Module Path is {$original_module_path}\n" ;
echo "New Module Path is ".$parent_module_path.$new_module."\n" ;

foreach ($original_module_files as $original_module_file) {

    $this_file = $original_module_path.DIRECTORY_SEPARATOR.$original_module_file ;

    if (is_file($this_file)) {
        echo "something ok. copy the file.\n" ;
        doAFileMove($original_module_path.DIRECTORY_SEPARATOR.$original_module_file, $original_module, $new_module) ;
    }

    else if (is_dir($this_file)) {
        echo "something ok. do the dir.\n" ;
        doADirMove($this_file, $original_module, $parent_module_path.$new_module, $new_module) ;
    }

    else {
        echo "something shit. apparently not a file or a dir.\n" ;
        exit(1) ;
    }

}

function doAFileMove($original_module_file, $original_module, $new_mod) {
    $target_file = str_replace($original_module, $new_mod, $original_module_file) ;
    echo "Doing a file move from {$original_module_file} to {$target_file} \n" ;
    $file_data = file_get_contents($original_module_file) ;
    $file_data = str_replace($original_module, $new_mod, $file_data) ;
    global $translates ;
    foreach ($translates as $search => $replace) {
        $file_data = str_replace($search, $replace, $file_data) ;  }
//    $target_file = translateFilename($target_file) ;
    $res = file_put_contents($target_file, $file_data) ;
//    var_dump("res:", $res, "tf", $target_file, "fd", $file_data) ;
}

function doADirMove($dir, $original_module, $target_dir, $new_module) {
    $end_dir = basename($dir) ;
    $target_dir .= DIRECTORY_SEPARATOR.$end_dir ;
    echo "Doing a dir move from ".$dir." to ".$target_dir." \n" ;
    echo "Making $target_dir \n" ;
    mkdir($target_dir, 0777, true) ;
    chmod($target_dir, 0777) ;
    $original_module_files = array_slice(scandir($dir), 2);
    foreach ($original_module_files as $original_module_file) {
        if (is_dir($dir.DIRECTORY_SEPARATOR.$original_module_file)) {
//            var_dump("start: ", $dir.DIRECTORY_SEPARATOR.$original_module_file, $target_dir) ;
            doADirMove($dir.DIRECTORY_SEPARATOR.$original_module_file, $original_module, $target_dir, $new_module) ; }
        else {
            doAFileMove($dir.DIRECTORY_SEPARATOR.$original_module_file, $original_module, $new_module) ; } }
}

function loopOurTranslates($translates) {

    $pairs = explode(",", $translates) ;
    $new_translates = array() ;
    foreach ($pairs as $pair) {
        $search = substr($pair, 0, strpos($pair, ":") ) ;
        $replace = substr($pair, strpos($pair, ":")+1 ) ;
        $new_translates[$search] = $replace ; }

    return $new_translates ;

}

function translateFilename($file_name) {
    $base = basename($file_name) ;
    $last_ds = strrpos ($file_name , DIRECTORY_SEPARATOR ) ;
    $path = substr($file_name, 0, $last_ds) ;
    global $translates ;
    $new_file_name = $path.DIRECTORY_SEPARATOR.strtr($base, $translates) ;
    return $new_file_name ;
}