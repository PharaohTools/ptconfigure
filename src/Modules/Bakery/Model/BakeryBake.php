<?php

Namespace Model;

class BakeryBake extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default", 'Bake') ;

    protected $bakery_details ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Bakery";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForVMName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForISOImagePath", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForOSType", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMemory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForVRam", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForCPUCount", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForSSHForwardingPort", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "runBakeryInstall", "params" => array()) ),
        );
        //@todo uninstall commands of bakery
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),);
        $this->programNameMachine = "bakery"; // command and app dir name
        $this->programNameFriendly = "Pharaoh Bakery"; // 12 chars
        $this->programNameInstaller = "The Image Baking Module";
        $this->statusCommand = 'bakery -version' ;
        $this->versionInstalledCommand = 'bakery -version 2>&1' ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy bakery" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy bakery" ;
        $this->initialize();
    }

    protected function runBakeryInstall() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $attached_ok = $this->attachISOToVM() ;
        if ($attached_ok !== true) {
            $logging->log('Unable to Attach ISO to VM', LOG_FAILURE_EXIT_CODE, $this->getModuleName()) ;
            return false ;
        }

        $unattended_install = $this->unattendedInstall() ;
        if ($unattended_install !== true) {
            $logging->log('Unattended Install Failed', LOG_FAILURE_EXIT_CODE, $this->getModuleName()) ;
            return false ;
        }

        return true ;
    }

    public function attachISOToVM() {
        $vm_name = $this->bakery_details['vm_name'] ;
        $iso_image = $this->bakery_details['iso'] ;
        $os_type = $this->bakery_details['ostype'] ;
        $memory = $this->bakery_details['memory'] ;
        $vram = $this->bakery_details['vram'] ;
        $cpu_count = $this->bakery_details['cpus'] ;
        $temp_ssh_forwarding_port = $this->bakery_details['ssh_forwarding_port'] ;

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $logging->log('VM Name is: '.$vm_name, LOG_FAILURE_EXIT_CODE, $this->getModuleName()) ;
        $logging->log('ISO Image is: '.$iso_image, LOG_FAILURE_EXIT_CODE, $this->getModuleName()) ;
        $profile_lines = array(
            VBOXMGCOMM.' unregistervm "'.$vm_name.'" --delete',
            VBOXMGCOMM.' createvm --name "'.$vm_name.'" --register'        ) ;

        foreach ($profile_lines as $profile_line) {
            $this->executeAndOutput($profile_line) ;
        }

        $vm_dir_comm  = 'VBoxManage showvminfo "'.$vm_name.'" | grep "^Config file:"  ' ;
        $vm_dir_comm .= '| awk -F":" \'{print $2}\' | xargs -L1 -IX dirname "X"' ;
        $vm_dir = self::executeAndLoad($vm_dir_comm) ;

        $profile_lines = array(
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --memory '.$memory.' --acpi on --boot1 disk --boot2 dvd --vram '.$vram.' --cpus '.$cpu_count ,
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --nic1 nat --nictype1 82540EM --cableconnected1 on',
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --natpf1 ",tcp,,'.$temp_ssh_forwarding_port.',,22"',
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --ostype '.$os_type,
            VBOXMGCOMM.' modifyvm "'.$vm_name.'"  --ioapic on',
            VBOXMGCOMM.' createhd --filename "'.$vm_dir.DIRECTORY_SEPARATOR.$vm_name.'.vdi" --size 80000',
            VBOXMGCOMM.' storagectl "'.$vm_name.'" --name "SATA" --add sata',
            VBOXMGCOMM.' storageattach "'.$vm_name.'" --storagectl "SATA" --port 0 --device 0 --type hdd --medium "'.$vm_dir.DIRECTORY_SEPARATOR.$vm_name.'.vdi"',
            VBOXMGCOMM.' storagectl "'.$vm_name.'" --name "IDE" --add ide',
            VBOXMGCOMM.' storageattach "'.$vm_name.'" --storagectl "IDE" --port 1 --device 0 --type dvddrive --medium "'.$iso_image.'"'
        ) ;

        foreach ($profile_lines as $profile_line) {
            $this->executeAndOutput($profile_line) ;
        }

    }

    public function unattendedInstall() {

        $vm_name = $this->bakery_details['vm_name'] ;
        $iso_image = $this->bakery_details['iso'] ;
        $ssh_user_name = 'ptv' ;
        $ssh_user_pass = 'ptv' ;
        $ssh_full_user = 'Pharaoh Virtualize' ;
        $locale = 'en_GB' ;
        $country = 'GB' ;
        $language = 'EN' ;
        $gui_mode = 'gui' ;

        $preseed_location = dirname(__DIR__) . DS . 'Templates'. DS . 'Ubuntu' . DS . 'preseed.cfg' ;
        $postinstall_location = dirname(__DIR__) . DS . 'Templates'. DS . 'Ubuntu' . DS . 'postinstall.sh' ;

        $comm  = VBOXMGCOMM.' unattended install ' ;
        $comm .= $vm_name.' ' ;
        $comm .= '--iso='.$iso_image.' ' ;
        $comm .= '--user="'.$ssh_user_name.'" ' ;
        $comm .= '--password="'.$ssh_user_pass.'" ' ;
        $comm .= '--full-user-name="'.$ssh_full_user.'" ' ;
        $comm .= '--script-template='.$preseed_location.' ' ;
        $comm .= '--post-install-template='.$postinstall_location.' ' ;
        $comm .= '--install-additions ' ;
        $comm .= '--locale='.$locale.' ' ;
        $comm .= '--country='.$country.' ' ;
        $comm .= '--language='.$language.' ' ;
        $comm .= '--start-vm='.$gui_mode.'' ;

        $profile_lines = array($comm) ;

        foreach ($profile_lines as $profile_line) {
            $this->executeAndOutput($profile_line) ;
        }

        return ;

    }


    protected function askForVMName() {
        if (isset($this->params["name"])) {
            $this->bakery_details['vm_name'] = $this->params["name"]; }
        else {
            $question = "Enter Name for Virtual Machine to create for Baking";
            $this->bakery_details['vm_name'] = self::askForInput($question, true); }
    }

    protected function askForISOImagePath() {
        if (isset($this->params["iso"])) {
            $this->bakery_details['iso'] = $this->params["iso"]; }
        else {
            $question = "Enter Path of ISO File for Virtual Machine";
            $this->bakery_details['iso'] = self::askForInput($question, true); }
    }

    protected function askForOSType() {
        if (isset($this->params["ostype"])) {
            $this->bakery_details['ostype'] = $this->params["ostype"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['ostype'] = 'Ubuntu_64'; }
        else {
            $question = "Enter Operating Sytem type (eg. Ubuntu_64)";
            $this->bakery_details['ostype'] = self::askForInput($question, true); }
    }

    protected function askForMemory() {
        if (isset($this->params["memory"])) {
            $this->bakery_details['memory'] = $this->params["memory"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['memory'] = '512'; }
        else {
            $question = "Enter RAM Size in Megabytes";
            $this->bakery_details['memory'] = self::askForInput($question, true); }
    }

    protected function askForVRam() {
        if (isset($this->params["vram"])) {
            $this->bakery_details['vram'] = $this->params["vram"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['vram'] = '33'; }
        else {
            $question = "Enter VRAM Size in Megabytes";
            $this->bakery_details['vram'] = self::askForInput($question, true); }
    }

    protected function askForCPUCount() {
        if (isset($this->params["cpus"])) {
            $this->bakery_details['cpus'] = $this->params["cpus"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['cpus'] = '1'; }
        else {
            $question = "Enter CPU Count";
            $this->bakery_details['cpus'] = self::askForInput($question, true); }
    }

    protected function askForSSHForwardingPort() {
        if (isset($this->params["ssh_forwarding_port"])) {
            $this->bakery_details['ssh_forwarding_port'] = $this->params["ssh_forwarding_port"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['ssh_forwarding_port'] = '1'; }
        else {
            $question = "Enter CPU Count";
            $this->bakery_details['ssh_forwarding_port'] = self::askForInput($question, true); }
    }

    public function versionInstalledCommandTrimmer($text) {
        $leftQuote = strpos($text, 'bakery version "') + 14 ;
        $rightQuote = strpos($text, '"', $leftQuote) ;
        $difference = $rightQuote - $leftQuote ;
        $done = substr($text, $leftQuote, $difference) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 53, 17) ;
        return $done ;
    }

}
