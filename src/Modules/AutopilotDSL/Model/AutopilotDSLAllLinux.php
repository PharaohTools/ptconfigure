<?php

Namespace Model;

class AutopilotDSLAllLinux extends BaseLinuxApp {

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
        $total_line_count = count($lines) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("About to parse $total_line_count lines", $this->getModuleName()) ;
        $trawl_line = 0 ;
        $total_loops = 0 ;
        while ( $trawl_line < $total_line_count && $total_loops < 10000) {
            $cur_lines_trawled = $this->trawlFile($lines, $trawl_line) ;
            $new_steps[] = $cur_lines_trawled["data"] ;
            // @todo below is for verbose logging
//            $logging->log("Current trawl line is {$trawl_line}", $this->getModuleName()) ;
            $trawl_line = $cur_lines_trawled["line"] ;
            $total_loops++ ; }
        return $new_steps ;
    }


    public function trawlFile($lines, $start_line) {
//    echo "Starting trawl... \n" ;
        $total = count($lines) ;
        $i = $start_line ;
        $parsedLine = $this->parseHeadLineText($lines[$i]) ;
//    echo "Headline Line is {$i} ... \n" ;
        $i2 = $i + 1 ;
//    echo "Param Start Line is {$i2} ... \n" ;
        $parsedParamsLine = $this->parseParamsText($lines, $i2, $total) ;
        $section = array_merge($parsedLine, array("params" => $parsedParamsLine["params"])) ;
        $i = $parsedParamsLine["line"] ;
//    echo "Param Finish Line is {$i} ... \n" ;
        return array("line" => $i, "data"  => $section) ;
    }

    public function loadFile($file_name) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Loading DSL Autopilot File", $this->getModuleName()) ;
        if (!file_exists($file_name)) {
            $logging->log("Something bad happened. The file $file_name does not exist", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        $lines = file($file_name) ;
        if (count($lines)==0) {
            $logging->log("Something bad happened. The file has no lines", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }
        return $lines ;
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
                    $this->getModuleName(),
                    LOG_FAILURE_EXIT_CODE) ;
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