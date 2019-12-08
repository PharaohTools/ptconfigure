<?php

namespace Model;

class SoftwareVersion {

    public $shortVersionNumber;
    public $fullVersionNumber;
    private $conditions;

    public function __construct($versionNumber) {
        $this->fullVersionNumber = $versionNumber ;
        $this->shortVersionNumber = $this->getShortVersionNumber($versionNumber) ;
    }

    private function getShortVersionNumber($versionNumber) {
        $shortNum = "" ;
        $chars = str_split($versionNumber) ;
        for ($i=0; $i < count($chars); $i++) {
            $is_int = in_array($chars[$i], array("1","2","3","4","5","6","7","8","9","0")) ;
            if ($is_int || $chars[$i] == ".") {
                $shortNum .= $chars[$i] ; }
            else {
                break ; } }
        return $shortNum ;
    }

    public function isGreaterThan(\Model\SoftwareVersion $compare) {
        if (is_object($compare) && $compare instanceof \Model\SoftwareVersion) {
            $myPieces = explode(".", $this->shortVersionNumber) ;
            $comparePieces = explode(".", $compare->shortVersionNumber) ;
            $highestCount = max($myPieces, $comparePieces);
            for ( $i=0 ; $i<=count($highestCount); $i++) {
                $cpInt = (isset($comparePieces[$i])) ? $comparePieces[$i] : 0 ;
                $mpInt = (isset($myPieces[$i])) ? $myPieces[$i] : 0 ;
                if ($cpInt > $mpInt ) {
                    return false ; }
                if ($cpInt < $mpInt ) {
                    return true ; }
                else {
                    continue; } } }
        return false ;
    }

    public function isLessThan(\Model\SoftwareVersion $compare) {
        if (is_object($compare) && $compare instanceof \Model\SoftwareVersion) {
            $myPieces = explode(".", $this->shortVersionNumber) ;
            $comparePieces = explode(".", $compare->shortVersionNumber) ;
            $highestCount = max($myPieces, $comparePieces);
            for ( $i=0 ; $i<=count($highestCount); $i++) {
                $cpInt = (isset($comparePieces[$i])) ? $comparePieces[$i] : 0 ;
                $mpInt = (isset($myPieces[$i]) ) ? $myPieces[$i] : 0 ;
                if ($cpInt < $mpInt ) {
                    return false ; }
                if ($cpInt > $mpInt ) {
                    return true ; }
                else {
                    continue; } } }
        return false ;
    }

    public function isEqualTo(\Model\SoftwareVersion $compare) {
        if (is_object($compare) && $compare instanceof \Model\SoftwareVersion) {
            if ( $this->shortVersionNumber != "" &&
                 $compare->shortVersionNumber != "" &&
                 $this->shortVersionNumber == $compare->shortVersionNumber) {
                return true ; }
            if ( $this->fullVersionNumber == $compare->fullVersionNumber) {
                return true ; }
            return false ; }
        else {
            return false; }
    }

    public function setCondition($version, $operation) {
        $this->conditions[] = array("version" => $version, "operation" => $operation) ;
    }

    public function isCompatible() {
        $conditionResults = array();
        foreach ($this->conditions as $condition) {
            $conditionVersion = $condition["version"];
            if (($condition["version"] instanceof \Model\SoftwareVersion) !== true ) {
                $conditionVersion = new \Model\SoftwareVersion($condition["version"]) ; }
            $op = $this->getOpFromSymbol($condition["operation"]) ;
            if ($op == "gt") {
                $conditionResults[] = ($this->isGreaterThan($conditionVersion) == true) ? true : false ;  }
            else if ($op == "lt") {
                $conditionResults[] = ($this->isLessThan($conditionVersion) == true) ? true : false ;  }
            else if ($op == "=") {
                $conditionResults[] = ($this->isEqualTo($conditionVersion) == true) ? true : false ;  } }
        return !in_array(false, $conditionResults) ;
    }

    protected function getOpFromSymbol($symbol) {
        if (in_array($symbol, array("gt", ">", "+"))) {
            return "gt" ;  }
        else if (in_array($symbol, array("lt", "<", "-"))) {
            return "lt" ;  }
        else if (in_array($symbol, array("=", "equals"))) {
            return "=" ;  }
        return null ;
    }

}