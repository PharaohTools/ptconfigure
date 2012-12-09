<?php

Namespace Core ;

class DatabaseHelpers {

    public function sanitize($varToSanitize) {
        if ( is_array($varToSanitize) ) {
            foreach ($varToSanitize as $key => $value) {
                $varToSanitize[$key] = mysql_real_escape_string($varToSanitize[$key]);
            }
            return $varToSanitize;
        } else {
            return mysql_real_escape_string($varToSanitize);
        }
        return false;
    }

    public function strReplaceOnce($needle , $replace , $haystack){
        $pos = strpos($haystack, $needle);
        if ($pos === false) { return $haystack;  }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

}