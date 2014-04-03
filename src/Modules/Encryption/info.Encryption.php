<?php

Namespace Info;

class EncryptionInfo extends Base {

    public $hidden = false;

    public $name = "Encryption or Decryption of files";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "Encryption" =>  array_merge(parent::routesAvailable() ) );
    }

    public function routeAliases() {
      return array("encryption"=>"Encryption", "encrypt"=>"Encryption");
    }

    public function autoPilotVariables() {
      return array(
        "Encryption" => array(
          "Encryption" => array(
            "programDataFolder" => "/opt/Encryption", // command and app dir name
            "programNameMachine" => "encrypt", // command and app dir name
            "programNameFriendly" => "Encryption", // 12 chars
            "programNameInstaller" => "Encryption",
          ),
        )
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command allows you to encrypt or decrypt a file.

  Encryption, encrypt

        - install
        Installs an encrypted file
        example: cleopatra encryption install

HELPDATA;
      return $help ;
    }

}