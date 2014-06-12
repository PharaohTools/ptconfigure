<?php

Namespace Info;

class GameBlocksInfo extends CleopatraBase {

    public $hidden = false;

    public $name = "Game Blocks Server Management Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "GameBlocks" => array_merge(parent::routesAvailable(), array("save-ssh-key",
          "box-add", "box-remove", "box-destroy", "box-destroy-all", "list") ) );
    }

    public function routeAliases() {
      return array("digitalocean"=>"GameBlocks", "game-blocks"=>"GameBlocks");
    }

    public function boxProviderName() {
        return "GameBlocks";
    }

    public function helpDefinition() {
       $help = <<<"HELPDATA"
    This is an extension provided for Handling Servers on Game Blocks.

    GameBlocks, digitalocean, game-blocks

        - box-add
        Lets you add boxes to Game Blocks, and adds them to your papyrusfile
        example: cleopatra game-blocks box-add
                    --yes
                    --game-blocks-ssh-key-path="/home/dave/.ssh/bastion.pub"
                    --game-blocks-ssh-key-name="bastion"

        - box-destroy
        Will destroy box/es in an environment for you, and remove them from the papyrus file
        example: cleopatra game-blocks box-destroy --yes --guess --game-blocks-ssh-key-path="/home/dave/.ssh/bastion.pub" --game-blocks-ssh-key-name="bastion"

        - box-destroy-all
        Will destroy all boxes in your digital ocean account - Careful - its irreversible
        example: cleopatra game-blocks box-destroy-all --yes --guess

        - save-ssh-key
        Will let you save a local ssh key to your Game Blocks account, so you can ssh in to your nodes
        securely and without a password
        example: cleopatra game-blocks save-ssh-key
                    --yes
                    --game-blocks-ssh-key-path="/home/dave/.ssh/bastion.pub"
                    --game-blocks-ssh-key-name="bastion"

        - list
        Will display data about your digital ocean account
        example: cleopatra game-blocks list
        example: cleopatra game-blocks list --yes
                    --guess # use project saved connection details if possible
                    --game-blocks-list-data-type=sizes # droplets, sizes, images, domains, regions, ssh_keys

HELPDATA;
      return $help ;
    }

}