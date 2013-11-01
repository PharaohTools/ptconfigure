<?php

Namespace Model;

class TemplatingUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Templating";
        $this->installCommands = array();
        $this->uninstallCommands = array();
        $this->programDataFolder = "";
        $this->programNameMachine = "templating"; // command and app dir name
        $this->programNameFriendly = "Templating !"; // 12 chars
        $this->programNameInstaller = "Templating Functionality";
        $this->initialize();
    }

    /*
     * @description create a file with inserted values from values and a template
     * @param $original a file path or string of data - a template
     * @param $replacements an array of replacements
     * @param $targetLocation a file path string to put the end file
     * @param $perms string
     */
    public function template($original, $replacements, $targetLocation, $perms = null, $owner = null, $group = null) {
        $fData = (is_file($original)) ? file_get_contents($original) : $original ;
        foreach ($replacements as $replaceKey => $replaceValue) {
            $fData = $this->replaceData($fData, $replaceKey, $replaceValue); }
        file_put_contents($targetLocation, $fData) ;
        if ($perms != null) { exec("chmod $perms $targetLocation"); }
        if ($owner != null) { exec("chown $owner $targetLocation"); }
        if ($group != null) { exec("chgrp $group $targetLocation"); }
    }

    public function replaceData($fData, $replaceKey, $replaceValue, $startTag='<%tpl.php%>', $endTag='</%tpl.php%>') {
        $lookFor = $startTag.$replaceKey.$endTag ;
        $fData = str_replace($lookFor, $replaceValue, $fData) ;
        return $fData ;
    }

}