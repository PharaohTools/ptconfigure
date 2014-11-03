<?php

Namespace Info;

class ChgrpInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Chgrp Functionality";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Chgrp" => array("path", "help") );
    }

    public function routeAliases() {
      return array("chgrp" => "Chgrp");
    }

  public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command handles file group ownership changing functions.

  Chgrp, chgrp

        - path
        Will change the file group ownership of a path
        example: cleopatra chgrp path --yes --guess --recursive --path=/a/file/path --group=golden


HELPDATA;
      return $help ;
    }

}