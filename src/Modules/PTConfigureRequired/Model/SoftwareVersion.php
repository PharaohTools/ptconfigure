<?php

namespace Model;

class SoftwareVersion {

    public $shortVersionNumber;
    public $fullVersionNumber;

    public function __construct($versionNumber) {
        $this->fullVersionNumber = $versionNumber ;
        $this->shortVersionNumber = $this->getShortVersionNumber($versionNumber) ;
    }

    // @todo this relies on php treating strings as arrays, which is deprecated innit?
    private function getShortVersionNumber($versionNumber) {
        $shortNum = "" ;
        for ($i=0; $i<strlen($versionNumber); $i++) {
            if (in_array($versionNumber[$i], array("1","2","3","4","5","6","7","8","9","0")) || $versionNumber[$i] == ".") {
                $shortNum .= $versionNumber[$i]; }
            else {
                break ; } }
        return $shortNum ;
    }

    //@todo this return seems wrong
    public function isGreaterThan($compare) {
        if (is_object($compare) && $compare instanceof SoftwareVersion) {
            $myPieces = explode(".", $this->shortVersionNumber) ;
            $comparePieces = explode(".", $compare->shortVersionNumber) ;
            for ( $i=0 ; $i<count($comparePieces); $i++) {
                if ($comparePieces[$i] > $myPieces[$i] ) {
                    return true ; }
                if ($comparePieces[$i] < $myPieces[$i] ) {
                    return false ; }
                else {
                    continue; } } }
        return "SoftwareVersion->isGreaterThan() Requires an instance of SoftwareVersion" ;
    }

    //@todo this return seems wrong
    public function isLessThan($compare) {
        if (is_object($compare) && $compare instanceof SoftwareVersion) {
            $myPieces = explode(".", $this->shortVersionNumber) ;
            $comparePieces = explode(".", $compare->shortVersionNumber) ;
            for ( $i=0 ; $i<count($comparePieces); $i++) {
                if ($comparePieces[$i] < $myPieces[$i] ) {
                    return true ; }
                if ($comparePieces[$i] > $myPieces[$i] ) {
                    return false ; }
                else {
                    continue; } } }
        return "SoftwareVersion->isLessThan() Requires an instance of SoftwareVersion" ;
    }

    //@todo this return seems wrong
    public function isCompatibleWith($compare) {
        if (is_object($compare) && $compare instanceof SoftwareVersion) {
            $myPieces = explode(".", $this->shortVersionNumber) ;
            $comparePieces = explode(".", $compare->shortVersionNumber) ;
            for ( $i=0 ; $i<count($comparePieces); $i++) {
                if ($comparePieces[$i] < $myPieces[$i] ) {
                    return true ; }
                if ($comparePieces[$i] > $myPieces[$i] ) {
                    return false ; }
                else {
                    continue; } } }
        return "SoftwareVersion->isCompatibleWith Requires an instance of SoftwareVersion" ;
    }


}