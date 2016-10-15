Logging log
  log-message "Lets configure PHP and Files for Pharaoh Source"

Mkdir" => array( "path" => array(
                    "label" => "Ensure the Repositories Directory exists",
                    "path" => REPODIR
                ), ), ),

                array ( "Chmod" => array( "path" => array(
                    "label" => "Ensure the Repositories Directory is writable",
                    "path" => REPODIR,
                    "recursive" => true,
                    "mode" => '0755',
                ), ), ),

                array ( "Logging" => array( "log" => array( "log-message" => "Configuration Management for Pharaoh Source Complete"),),),

            );

    }

}
