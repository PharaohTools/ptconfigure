<?php

Namespace Core ;

class DatabaseHelpers {

    public function sanitize($varToSanitize) {
        if ( is_array($varToSanitize) ) {
            foreach ($varToSanitize as $key => $value) {
                $varToSanitize[$key] = mysql_real_escape_string($value); }
            return $varToSanitize; }
        else {
            return mysql_real_escape_string($varToSanitize); }
        return false;
    }

}