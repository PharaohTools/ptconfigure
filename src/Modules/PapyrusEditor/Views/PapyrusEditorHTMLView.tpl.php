
        <h1>Papyrus File Editor</h1>

        <?php

        echo '<form method="POST" action="/">' ;

        if (!isset($_REQUEST["papyrus_location"])) {
            echo '<p> Enter Papyrus file location </p>  <input name="papyrus_location" type="text"> </input>' ;

            echo '  <input type="submit" value="Load this file" />' ;
        }

        else if (isset($_REQUEST["dosave"]) && $_REQUEST["dosave"]=="on") {
            var_dump($_REQUEST) ;
        }

        else if (isset($_REQUEST["papyrus_location"])) {

            echo '<p>  File at location '.$_REQUEST["papyrus_location"].': </p> ' ;

            echo '<input type="hidden" name="papyrus_location" value="'.$_REQUEST["papyrus_location"].'" />' ;

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
                                     foreach ($papy_subsubval as $papy_subsubsubkey => $papy_subsubsubval) {
                                         if (is_array($papy_subsubsubval)) {
                                                 foreach ($papy_subsubsubval as $papy_subsubsubsubkey => $papy_subsubsubsubval) {
                                                     echo '  <p>---'.$papy_subsubsubsubkey.'' ;
                                                     echo '  <input name="'.$papy_key.'['.$papy_subkey.']['.$papy_subsubkey.']['.$papy_subsubsubkey.']['.$papy_subsubsubsubkey.'" value="'.$papy_subsubsubsubval.'" /> </p>' ; } }
                                         else {
                                             echo '  <p>---'.$papy_subsubsubkey.'' ;
                                             echo '  <input name="'.$papy_key.'['.$papy_subkey.']['.$papy_subsubkey.']['.$papy_subsubsubkey.']" value="'.$papy_subsubsubval.'" /> </p>' ; } } }
                                 else {
                                     echo '  <p>--'.$papy_subsubkey.'</p>' ;
                                     echo '  <input name="'.$papy_key.'['.$papy_subkey.']['.$papy_subsubkey.']" value="'.$papy_subsubval.'" /> </p>' ; } } }
                         else {
                             echo '  <p>--'.$papy_subkey.'</p>' ;
                             echo '  <input name="'.$papy_key.'['.$papy_subkey.']" value="'.$papy_subval.'" />' ; } } }
                else {
                    echo '  <p>--'.$papy_key.'</p>' ;
                    echo '  <input name="'.$papy_key.'" value="'.$papy_val.'" />' ; }

                echo '</div>' ; }

            echo '  <input type="hidden" name="dosave" value="on" />' ;
            echo '  <input type="submit" value="Save this file" />' ;
        }

        echo '</form>' ;


        function spitray() {

        }