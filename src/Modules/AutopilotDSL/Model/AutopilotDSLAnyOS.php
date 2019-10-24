<?php

Namespace Model;

class AutopilotDSLAnyOS extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function loopOurDSLFile($file) {
        $lines = $this->loadFile($file) ;
        $new_steps = array() ;
        $new_vars = array() ;
        $total_line_count = count($lines) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("About to parse $total_line_count lines from {$file}\n\n", $this->getModuleName()) ;
        $start_time = time() ;
        $date_format = date('H:i:s, d/m/Y', $start_time) ;
        $logging->log("Execution started at {$date_format}\n\n", $this->getModuleName()) ;
        $trawl_line = 0 ;
        $total_loops = 0 ;
        while ( $trawl_line < $total_line_count && $total_loops < 10000) {
            $cur_lines_trawled = $this->trawlFile($lines, $trawl_line) ;
            if (isset($cur_lines_trawled["data"])) {
                $new_steps[] = $cur_lines_trawled["data"] ; }
            else if (isset($cur_lines_trawled["name"]) && $cur_lines_trawled["name"] !=="" ) {
                $new_vars[$cur_lines_trawled["name"]] = $cur_lines_trawled["value"] ; }
            else if (isset($cur_lines_trawled["comments"])) {
//                var_dump("the new vars", $cur_lines_trawled) ;
            }
            // @todo below is for verbose logging
            // $logging->log("Current trawl line is {$trawl_line}", $this->getModuleName()) ;
            $trawl_line = $cur_lines_trawled["line"] ;
            $total_loops++ ; }
        return array("vars" => $new_vars, "steps" => $new_steps) ;
    }


    public function trawlFile($lines, $start_line) {
        $total = count($lines) ;
        $i = $start_line ;
        // allow comments
        // @todo fix comments
        $res = $this->parseComments($lines, $i) ;
        if ($res !== false) {
            $i = $res["line"] + 1 ; }
        $parsedLine = $this->parseHeadLineText($lines[$i]) ;
        $i2 = $i + 1 ;
        $parsedParamsLine = $this->parseParamsText($lines, $i2, $total) ;
        $section = array_merge($parsedLine, array("params" => $parsedParamsLine["params"])) ;
        $i = $parsedParamsLine["line"] ;
        return array("line" => $i, "data"  => $section) ;
    }

    public function loadFile($file_name) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading DSL Autopilot File", $this->getModuleName()) ;
        if (substr(php_uname(), 0, 7) == "Windows") {
            $file_name = str_replace("^\\", '\\\\', $file_name) ;
        }
        if (!file_exists($file_name)) {
            $logging->log("Something bad happened. The file $file_name does not exist", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $lines = file($file_name) ;
        if (count($lines)==0) {
            $logging->log("Something bad happened. The file has no lines", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        return $lines ;
    }

    public function parseVariables($line) {
        $parts = array() ;
        $tab_free = $this->getTabFreeLine($line) ;
        $stend_sp_free = $this->removeStartAndEndSpaces($tab_free) ;
        if (substr($stend_sp_free, 0, 1) == '$') {
            $words_in_line = $this->getShortWords($stend_sp_free) ;
//            var_dump($words_in_line) ;
            if (count($words_in_line)==3) {
                $parts["name"] = $words_in_line[0] ;
                $parts["value"] = $words_in_line[2] ; }
            else if (count($words_in_line)==2) {
                $parts["name"] = $words_in_line[0] ;
                $parts["value"] = $words_in_line[1] ; }
            else {
                // this is probably an error
                return false ; }
            return $parts ; }
        else {
            return false ; }
    }

    public function parseHeadLineText($line) {
        $parts = array() ;
        $tab_free = $this->getTabFreeLine($line) ;
        $stend_sp_free = $this->removeStartAndEndSpaces($tab_free) ;
        $words_in_line = $this->getShortWords($stend_sp_free) ;
        if (count($words_in_line)==3) {
            $parts["module"] = $words_in_line[0] ;
            $parts["action"] = $words_in_line[2] ; }
        if (count($words_in_line)==2) {
            $parts["module"] = $words_in_line[0] ;
            $parts["action"] = $words_in_line[1] ; }
        return $parts ;
    }

    public function parseComments($lines, $start) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $current = $start ;
//        $logging->log("Starting comment trawl...", $this->getModuleName()) ;
        $first_char = substr($lines[$start], 0, 1) ;
        if ($first_char != '#') { return false ; }
        for ( $i = $start ; $i < count($lines) ; $i++ ) {
            $current = $i ;
//            var_dump($first_char, $lines[$i], $i) ;
            $first_char = substr($lines[$i], 0, 1) ;
            if ($first_char != '#') { break ; }}
        $show_start = $start + 1 ;
        $logging->log("Ignoring commented lines $show_start until $current... ", $this->getModuleName()) ;
        return array( "line" => $current, "comments" => true) ;
    }

    public function parseParamsText($lines, $start, $total) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
//    var_dump("param parse: ", "start: ",$start, "total", $total) ;
        $params = array() ;
        $current = $start ;
        for ( $i = $start ; $i < $total ; $i++ ) {
            $tab_free = $this->getTabFreeLine($lines[$i]) ;
            $stend_sp_free = $this->removeStartAndEndSpaces($tab_free) ;
            $words_in_line = $this->getLongWords($stend_sp_free) ;
            if (count($words_in_line)==3) {
                $params[$words_in_line[0]] = $words_in_line[2] ; }
            else if (count($words_in_line)==2) {
                $params[$words_in_line[0]] = $words_in_line[1] ; }
            else if (count($words_in_line)==1 && $words_in_line[0] !=="") {
                $params[$words_in_line[0]] = "true" ; }
            else if (count($words_in_line)==1 && $words_in_line[0] =="") {
                break ; }
            if (strlen($stend_sp_free)==0) {
                break ; }
            $current = $i ; }
        $current++;
//    var_dump("current last line:", $current) ;
        $max_line = $current + 10 ;
        $newline = $current ;
        for ($ix = $current; $ix <= $max_line ; $ix++) {
            if ($ix >= count($lines)) {
                break ; }
            $newline = $ix ;
            $tab_free = $this->getTabFreeLine($lines[$ix]) ;
            $stend_sp_free = $this->removeStartAndEndSpaces($tab_free) ;
//        var_dump("stf: ", $stend_sp_free) ;
            if (strlen($stend_sp_free) > 0) {
                break ; }
//        echo "$ix\n" ;
            if ($ix == $max_line) {
                $logging->log(
                    "Autopilot DSL File has too many consecutive newlines at line {$ix}",
                    $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ; } }
        return array("params" => $params, "line" => $newline ) ;
    }

    public function isOnlyWhitespace($line) {
        $tab_free = str_replace("\t", "", $line) ;
        return $tab_free ;
    }

    public function getTabFreeLine($line) {
        $tab_free = str_replace("\t", "", $line) ;
        return $tab_free ;
    }

    public function removeStartAndEndSpaces($line) {
        $stend_sp_free = ltrim($line) ;
        $stend_sp_free = rtrim($stend_sp_free) ;
        return $stend_sp_free ;
    }

    public function getShortWords($line) {
        $words = explode(" ", $line) ;
        return $words ;
    }

    public function getLongWords($line) {
        $words = explode(" ", $line) ;
        $tmp_words = $words ;

        if (count($words) < 3) {
            return $words ; }

        $ws2 = $this->wordStartsString($words[2]) ;
        $ws1 = $this->wordStartsString($words[1]) ;

        $new_words = array() ;
        $new_words[0] = $words[0] ;
        $new_words[1] = $words[1] ;

        if ($ws2 !== false) {
            unset($tmp_words[0]) ;
            unset($tmp_words[1]) ;
            $dl = $ws2 ; }
        else if ($ws1 !== false) {
            unset($tmp_words[0]) ;
            $dl = $ws1 ; }
        else {
            return $new_words ; }

        $third_word = implode(" ", $tmp_words) ;
        $third_word = rtrim($third_word, $dl) ;
        $third_word = ltrim($third_word, $dl) ;
        $new_words[2] = $third_word ;
        return $new_words ;
    }

    protected function wordStartsString($word) {
        $fc = substr($word, 0, 1) ;
        if ($fc=='"') { return $fc ; }
        if ($fc=="'") { return $fc ; }
        return false ;
    }

}