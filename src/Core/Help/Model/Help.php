<?php

Namespace Model;

class Help {

    public function getHelpData($module) {
        $infoObject = \Core\AutoLoader::getSingleInfoObject($module);
        return $infoObject->helpDefinition();
    }

}