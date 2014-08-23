<?php

Namespace Model;

class PapyrusEditorInstallInterface extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("InstallPapyrusEditorInterface") ;

    protected $url ;

    public function askWhetherToInstallInterface($params=null) {
        return $this->performPapyrusEditorInstallInterface($params);
    }

    public function performPapyrusEditorInstallInterface($params=null){
        if ($this->askForPapyrusEditorInterfaceExecute() != true) { return false; }
        $this->url = $this->askForPapyrusEditorUrl();
        $this->installInterface();
        return true ;
    }

    private function askForPapyrusEditorInterfaceExecute(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Install Papyrus Editor Statistics Monitor?';
        return self::askYesOrNo($question);
    }

    private function askForPapyrusEditorUrl() {
        if (isset($this->params["papyruseditor-url"])) { return $this->params["papyruseditor-url"] ; }
        if (isset($this->params["guess"])) { return "www.papyruseditor.tld" ; }
        $question = 'Enter Local URL To use for PapyrusEditor Stats';
        return self::askForInput($question, true);
    }

    public function installInterface() {
        $autofile = str_replace("Model", "Autopilots/Dapperstrano/install-papyruseditor-local.php", dirname(__FILE__)) ;
        $comm  = 'sudo dapperstrano autopilot execute --autopilot-file="'.$autofile.'" --papyruseditor-url="'.$this->url ;
        $comm .= '" --source-dir='.dirname(dirname(__FILE__)) ;
        return $this->executeAndOutput($comm) ;
    }

}
