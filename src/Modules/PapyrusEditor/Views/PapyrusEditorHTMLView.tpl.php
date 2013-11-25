
        <h1>Papyrus File Editor</h1>

        <?php

        echo '<form method="POST" action="/">' ;

        if (!isset($_REQUEST["doLoad"]) && !isset($_REQUEST["doSave"])) {
            echo '<p> Enter Papyrus file location </p>   <input size="60" name="papyrus_location" type="text"> </input>' ;

            echo '  <input type="hidden" name="doLoad" value="on" />' ;
            echo '  <input type="submit" value="Load this file" />' ;
        }

        else if (isset($_REQUEST["doLoad"]) && $_REQUEST["doLoad"]=="on") {

            echo '<p> Papyrus File load location: '.$_REQUEST["papyrus_location"].' </p> ' ;
            $p_save_loc = (isset($_REQUEST["papyrus_save_location"])) ? $_REQUEST["papyrus_save_location"] : null ;
            echo '<p> Papyrus File save location: <input size="60" type="text" name="papyrus_save_location" value="'.$p_save_loc.'" /> </p>' ;

            foreach ($pageVars["current_papyrus"] as $papy_key => $papy_val) {
                echo '<div>' ;
                echo '  <p>+'.$papy_key.'</p>' ;

                if (is_array($papy_val)) {
                     foreach ($papy_val as $papy_subkey => $papy_subval) {
                         // echo '<p>-'.$papy_subkey.'</p>' ;
                         if (is_array($papy_subval)) {
                             foreach ($papy_subval as $papy_subsubkey => $papy_subsubval) {
                                 echo '  <p>--'.$papy_subsubkey.'</p>' ;
                                 if (is_array($papy_subsubval)) {
                                     // $akey = array_keys($papy_subsubval) ;
                                     // if (is_numeric($akey[0])) { echo "foff;"; }
                                     foreach ($papy_subsubval as $papy_subsubsubkey => $papy_subsubsubval) {
                                         if (is_array($papy_subsubsubval)) {
                                             foreach ($papy_subsubsubval as $papy_subsubsubsubkey => $papy_subsubsubsubval) {
                                                 echo '  <p>---'.$papy_subsubsubsubkey.'' ;
                                                 echo '   <input size="60" name="'.$papy_key.'['.$papy_subkey.']['.$papy_subsubkey.']['.$papy_subsubsubkey.']['.$papy_subsubsubsubkey.']" value="'.$papy_subsubsubsubval.'" /> </p>' ; } }
                                         else {
                                             echo '  <p>---'.$papy_subsubsubkey.'' ;
                                             echo '   <input size="60" name="'.$papy_key.'['.$papy_subkey.']['.$papy_subsubkey.']['.$papy_subsubsubkey.']" value="'.$papy_subsubsubval.'" /> </p>' ; } }
                                     $i++; }
                                 else {
                                     echo '  <p>--'.$papy_subsubkey.'</p>' ;
                                     echo '   <input size="60" name="'.$papy_key.'['.$papy_subkey.']['.$papy_subsubkey.']" value="'.$papy_subsubval.'" /> </p>' ; } } }
                         else {
                             echo '  <p>--'.$papy_subkey.'</p>' ;
                             echo '   <input size="60" name="'.$papy_key.'['.$papy_subkey.']" value="'.$papy_subval.'" />' ; } } }
                else {
                    echo '  <p>--'.$papy_key.'</p>' ;
                    echo '   <input size="60" name="'.$papy_key.'" value="'.$papy_val.'" />' ; }

                echo '</div>' ; }

            echo '  <input type="hidden" name="doSave" value="on" />' ;
            echo '  <input type="submit" value="Save this file" />' ;
        }

        else if (isset($_REQUEST["doSave"]) && $_REQUEST["doSave"]=="on") {
            var_dump($_REQUEST) ;
        }

        echo '</form>' ;