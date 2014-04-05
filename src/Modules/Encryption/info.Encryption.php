<?php

Namespace Info;

class EncryptionInfo extends CleopatraBase {

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
        Encrypts a file or string
        example: sudo cleopatra encryption install --yes --unencrypted-data=/var/www/a-website/build/config/cleopatra/SSH/raw/bastion
                 --encryption-target-file=/tmp/encrypted --encryption-key=/root/.cleopatra/SSH/key --encryption-file-permissions=""
                 --encryption-file-owner="" --encryption-file-group=""

        - uninstall
        Decrypts an encrypted file or string
        example: sudo cleopatra encryption uninstall --yes --encrypted-data=/tmp/encrypted
                 --encryption-target-file=/var/www/a-website/build/config/cleopatra/SSH/raw/bastion --encryption-key=/root/.cleopatra/SSH/key
                 --encryption-file-permissions="" --encryption-file-owner="" --encryption-file-group=""

HELPDATA;
      return $help ;
    }

}