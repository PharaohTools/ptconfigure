<?php

Namespace Model;

class NginxControlLinuxMac extends Base {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $vHostTemplate;
    private $docRoot;
    private $url;
    private $vHostIp;
    private $vHostForDeletion;
    private $vHostEnabledDir;
    private $vHostDir = '/etc/nginx2/sites-available' ; // no trailing slash

    public function askWhetherToCtlNginx($ctl) {
        if ( !$this->askForNginxCtl($ctl) ) { return false; }
        $this->{$ctl."Nginx"}();
        return true;
    }

    private function askForNginxCtl($type) {
      if (!in_array($type, array("start", "stop", "restart"))) { return false; }
      if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
      $question = 'Do you want to '.ucfirst($type).' Nginx?';
      return self::askYesOrNo($question);
    }

    private function enableVHost(){
        $command = 'nginx_ensite '.$this->url;
        self::executeAndOutput($command, "a2ensite $this->url done");
        $vHostEnabledDir = (isset($vHostEditorAdditionSymLinkDirectory)) ?
            $vHostEditorAdditionSymLinkDirectory : str_replace("sites-available", "sites-enabled", $this->vHostDir );
        $command = 'sudo ln -s '.$this->vHostDir.'/'.$this->url.' '.$vHostEnabledDir.'/'.$this->url;
        return self::executeAndOutput($command, "VHost Enabled/Symlink Created if not done by a2ensite");
    }

    private function disableVHost(){
        foreach ($this->vHostForDeletion as $vHost) {
            $command = 'nginx_dissite '.$vHost;
            self::executeAndOutput($command, "a2dissite $vHost done"); }
        return true;
    }

    private function restartNginx(){
        echo "Restarting Nginx...\n";
        $command = "sudo service nginx restart";
        return self::executeAndOutput($command);
    }

    private function startNginx(){
        echo "Starting Nginx...\n";
        $command = "sudo service nginx start";
        return self::executeAndOutput($command);
    }


    private function stopNginx(){
        echo "Stopping Nginx...\n";
        $command = "sudo service nginx stop";
        return self::executeAndOutput($command);
    }

}