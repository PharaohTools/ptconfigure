<?php

    foreach ($pageVars["result"] as $one_environment) {
		echo "Environment Name: ".$one_environment["any-app"]["gen_env_name"]."\n" ;	   
	    foreach ($one_environment as $root_key => $values_for_root_key) {
			echo " $root_key\n" ;
			foreach ($values_for_root_key as $sub_key => $sub_value) {
				if (is_array($sub_value)) {
					
					echo "  $sub_key\n" ;
					foreach ($sub_value as $minor_key => $minor_val) {
						echo "   $minor_key $minor_val \n" ; } }
				else {
					echo "  $sub_key $sub_value\n" ; } } }
	    echo "\n\n" ; }

?>

In Environment Configuration
