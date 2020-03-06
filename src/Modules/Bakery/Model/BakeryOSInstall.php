<?php

Namespace Model;

class BakeryOSInstall extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("OSInstall") ;

    protected $bakery_details ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Bakery";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForVMName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForISOImagePath", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForOSType", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForOSVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMemory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForVRam", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForCPUCount", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForSSHForwardingPort", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForUserPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForFullUser", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForLocale", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForCountry", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForLanguage", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForGUIMode", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForNotifyDelay", "params" => array()) ),
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
            $logging->log('Unable to Attach ISO to VM', $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;
        }

        $unattended_install = $this->unattendedInstall() ;
        if ($unattended_install !== true) {
            $logging->log('Starting Unattended Install Failed', $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ;
        }

        $completion_ok = $this->waitForInstallCompletion() ;
        if ($completion_ok !== true) {
            $logging->log('Completing Unattended Install Failed', $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
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

        $logging->log('VM Name is: '.$vm_name, $this->getModuleName()) ;
        $logging->log('ISO Image is: '.$iso_image, $this->getModuleName()) ;
        $profile_line = VBOXMGCOMM.' list runningvms' ;
        $raw = $this->executeAndLoad($profile_line) ;
        $lines = explode("\n", $raw) ;

        foreach ($lines as $line) {
            $vm_found = (strpos($line, '"'.$vm_name.'"') !== false) ? true : false ;
            if ($vm_found === true) {
                $message = "VM {$vm_name} is Running in Provider. Stopping." ;
                $logging->log($message, $this->getModuleName()) ;
                $stop_comm = VBOXMGCOMM.' controlvm "'.$vm_name.'" poweroff' ;
                $res = $this->executeAsShell($stop_comm) ;
                if ($res !== 0) {
                    $message = "Command Failed: $stop_comm" ;
                    $logging->log($message, $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                }
                sleep(3) ; # replace this with a check that it has actually stopped
            }
        }

        $profile_line = VBOXMGCOMM.' list vms' ;
        $raw = $this->executeAndLoad($profile_line) ;
        $lines = explode("\n", $raw) ;

        foreach ($lines as $line) {
            $vm_found = (strpos($line, '"'.$vm_name.'"') !== false) ? true : false ;
            if ($vm_found === true) {
                $message = "Found VM {$vm_name} in Provider. Removing." ;
                $logging->log($message, $this->getModuleName()) ;
                $unreg_comm = VBOXMGCOMM.' unregistervm "'.$vm_name.'" --delete || true > /dev/null' ;
                for ($i=0; $i<2; $i++) {
                    $res = $this->executeAsShell($unreg_comm) ;
                    if ($res !== 0) {
                        $message = "Command Failed: $unreg_comm" ;
                        $logging->log($message, $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                    }
                }
            }
        }

        $vm_create_comm = VBOXMGCOMM.' createvm --name "'.$vm_name.'" --register' ;
        $this->executeAndOutput($vm_create_comm) ;
        $vm_dir_comm  = VBOXMGCOMM.' showvminfo "'.$vm_name.'" | grep "^Config file:"  ' ;
        $vm_dir_comm .= '| awk -F":" \'{print $2}\' | xargs -L1 -IX dirname "X"' ;
        $vm_dir = self::executeAndLoad($vm_dir_comm) ;

        $profile_lines = array(
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --memory '.$memory.' --acpi on --boot1 disk --boot2 dvd --vram '.$vram.' --cpus '.$cpu_count ,
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --nic1 nat --nictype1 82540EM --cableconnected1 on',
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --natpf1 ",tcp,,'.$temp_ssh_forwarding_port.',,22"',
            VBOXMGCOMM.' modifyvm "'.$vm_name.'" --ostype '.$os_type,
            VBOXMGCOMM.' modifyvm "'.$vm_name.'"  --ioapic on',
            VBOXMGCOMM.' createhd --filename "'.$vm_dir.DS.$vm_name.'.vdi" --size 80000',
            VBOXMGCOMM.' storagectl "'.$vm_name.'" --name "SATA" --add sata',
            VBOXMGCOMM.' storageattach "'.$vm_name.'" --storagectl "SATA" --port 0 --device 0 --type hdd --medium "'.$vm_dir.DS.$vm_name.'.vdi"',
            VBOXMGCOMM.' storagectl "'.$vm_name.'" --name "IDE" --add ide',
            VBOXMGCOMM.' storageattach "'.$vm_name.'" --storagectl "IDE" --port 1 --device 0 --type dvddrive --medium "'.$iso_image.'"'
        ) ;

        foreach ($profile_lines as $profile_line) {
            sleep(1) ;
            $res = $this->executeAsShell($profile_line) ;
            if ($res !== 0) {
                $message = "Command Failed: $profile_line" ;
                $logging->log($message, $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                return false ;
            }
        }

        return true ;

    }

    public function unattendedInstall() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $vm_name = $this->bakery_details['vm_name'] ;
        $underscore_pos = strpos($this->bakery_details['ostype'], '_') ;
        $os_string = substr($this->bakery_details['ostype'], 0, $underscore_pos) ;
        $os_version_string = $this->bakery_details['osversion'] ;
        if ($os_string === 'Ubuntu') {
            if (strpos($os_version_string, '.') !== false) {
                $os_version_string = str_replace('.', DS, $os_version_string) ;
            }
        }
        $full_preseed_location = dirname(__DIR__) . DS . 'Templates'. DS . $os_string . DS . $os_version_string . DS . 'preseed.cfg' ;
        $generic_preseed_location = dirname(__DIR__) . DS . 'Templates'. DS . $os_string . DS . 'preseed.cfg' ;
        if (file_exists($full_preseed_location)) {
            $preseed_location = $full_preseed_location ;
        } else {
            $preseed_location = $generic_preseed_location ;
        }
        $logging->log("Preseed Location: {$preseed_location}", $this->getModuleName() ) ;

        $full_postinstall_location = dirname(__DIR__) . DS . 'Templates'. DS . $os_string . DS . $os_version_string . DS . 'postinstall.sh' ;
        $generic_postinstall_location = dirname(__DIR__) . DS . 'Templates'. DS . $os_string . DS . 'postinstall.sh' ;
        if (file_exists($full_postinstall_location)) {
            $postinstall_location = $full_postinstall_location ;
        } else {
            $postinstall_location = $generic_postinstall_location ;
        }
        $logging->log("PostInstall Script Location: {$postinstall_location}", $this->getModuleName() ) ;

        // d-i pkgsel/install-language-support boolean false

        $comm  = VBOXMGCOMM.' unattended install ' ;
        $comm .= $vm_name.' ' ;
        $comm .= '--iso='.$this->bakery_details['iso'].' ' ;
        $comm .= '--user="'.$this->bakery_details['user_name'].'" ' ;
        $comm .= '--password="'.$this->bakery_details['user_pass'].'" ' ;
        $comm .= '--full-user-name="'.$this->bakery_details['full_user'].'" ' ;
        $comm .= '--script-template='.$preseed_location.' ' ;
        $comm .= '--post-install-template='.$postinstall_location.' ' ;
        $comm .= '--install-additions ' ;
        $comm .= '--locale='.$this->bakery_details['locale'].' ' ;
        $comm .= '--country='.$this->bakery_details['country'].' ' ;
        $comm .= '--language='.$this->bakery_details['language'].' ' ;
        $comm .= '--start-vm='.$this->bakery_details['gui_mode'].'' ;

        $logging->log("Command: {$comm}", $this->getModuleName() ) ;

        $descriptors = array(
            0 => array("pipe", "r"),  // STDIN
            1 => array("pipe", "w"),  // STDOUT
            2 => array("pipe", "w")   // STDERR
        );
        $process = proc_open($comm, $descriptors, $pipes) ;

        $timeout = 60 ; # 1 Minute
        $logging->log("Waiting for Unattended Install to start", $this->getModuleName() ) ;
        sleep(5) ;
        $start_ok = false ;
        $has_counted = false ;
        for ($seconds_passed = 0; $seconds_passed < $timeout ; $seconds_passed ++) {
            $status = proc_get_status($process) ;
            $logging->log(var_export($status, true), $this->getModuleName() ) ;
            if ($status['running'] === true) {
                $has_counted = true ;
                echo '.' ;
                sleep(1) ;
            } else if ($status['running'] === false && $status['exitcode'] === 1) {
                $logging->log('Failed to start the Virtal Machine', $this->getModuleName(), LOG_FAILURE_EXIT_CODE ) ;
                return false ;
            } else {
                $start_ok = true ;
                break ;
            }
        }
        if ($has_counted == true) {
            echo "\n" ;
        }
        if ($start_ok == true) {
            return true;
        }
        return false;
    }

    public function waitForInstallCompletion() {
        $timeout = 60 * 120 ; # 2 hours
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Installation has started", $this->getModuleName() ) ;
        sleep(1) ;
        $vm_name = $this->bakery_details['vm_name'] ;

        $ua_file_path = '/tmp/ptv_unattended_complete' ;
        $installed_check = 'ls -1 '.$ua_file_path ;

        $dump_path = '/tmp/unattended_tmp_status_'.$vm_name ;
        $comm = VBOXMGCOMM.' guestcontrol "'.$vm_name.'" run --exe "'.$installed_check.'" > '.$dump_path . ' 2>&1';
        $completion_ok = false ;
        $check_delay = 10 ;
        $needle = 'The guest execution service is not ready (yet)' ;
        $needle_not_running = 'Machine "'.$vm_name.'" is not running' ;
        $notify_waited = 0 ;
        for ($seconds_passed = 0; $seconds_passed < $timeout ; $seconds_passed += $check_delay ) {
            $res = self::executeAsShell($comm) ;
            $data = file_get_contents($dump_path) ;
            if (strpos($data, $needle) !== false) {
                if ($notify_waited >= $this->bakery_details['notify-delay']) {
                    $notify_waited = 0 ;
                    $logging->log("Waiting for Guest Execution to be ready, {$seconds_passed} Seconds", $this->getModuleName() ) ;
                }
                sleep($check_delay) ;
                $notify_waited += $check_delay ;
            } else if (strpos($data, $needle_not_running)) {
                $logging->log('Machine "'.$vm_name.'" is not running', $this->getModuleName() ) ;
                $logging->log($data, $this->getModuleName() ) ;
                $completion_ok = true ;
                break ;
            } else if ($res !== $ua_file_path) {
                $logging->log("Installing, {$seconds_passed} Seconds", $this->getModuleName() ) ;
                $logging->log($data, $this->getModuleName() ) ;
                sleep($check_delay) ;
            }
        }
        echo "\n" ;
        if ($completion_ok == true) {
            return true;
        }
        return false;
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

    protected function askForOSVersion() {
        if (isset($this->params["osversion"])) {
            $this->bakery_details['osversion'] = $this->params["osversion"] ; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            if ( strpos($this->bakery_details['ostype'], 'Ubuntu') == 0 ) {
                $this->bakery_details['osversion'] = '18.04';
            } else if ( strpos($this->bakery_details['ostype'], 'Centos') == 0 ) {
                $this->bakery_details['osversion'] = '7'  ;
            } else {
                $this->bakery_details['osversion'] = '' ;
            } }
        else {
            $question = "Enter Operating Sytem Release Version (eg. 18, 18.04 for Ubuntu or 6/7 for Centos)";
            $this->bakery_details['osversion'] = self::askForInput($question, true) ; }
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
            $question = "Enter SSH Forwarding Port";
            $this->bakery_details['ssh_forwarding_port'] = self::askForInput($question, true); }
    }

    protected function askForUserName() {
        if (isset($this->params["user_name"])) {
            $this->bakery_details['user_name'] = $this->params["user_name"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['user_name'] = 'ptv'; }
        else {
            $question = "Enter Default User Name";
            $this->bakery_details['user_name'] = self::askForInput($question, true); }
    }

    protected function askForUserPass() {
        if (isset($this->params["user_pass"])) {
            $this->bakery_details['user_pass'] = $this->params["user_pass"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['user_pass'] = 'ptv'; }
        else {
            $question = "Enter Default User Password";
            $this->bakery_details['user_pass'] = self::askForInput($question, true); }
    }

    protected function askForFullUser() {
        if (isset($this->params["full_user"])) {
            $this->bakery_details['full_user'] = $this->params["full_user"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['full_user'] = 'Pharaoh Virtualize'; }
        else {
            $question = "Enter CPU Count";
            $this->bakery_details['full_user'] = self::askForInput($question, true); }
    }

    protected function askForLocale() {
        if (isset($this->params["locale"])) {
            $this->bakery_details['locale'] = $this->params["locale"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['locale'] = 'en_GB'; }
        else {
            $question = "Enter CPU Count";
            $this->bakery_details['locale'] = self::askForInput($question, true); }
    }


    protected function askForCountry() {
        if (isset($this->params["country"])) {
            $this->bakery_details['country'] = $this->params["country"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['country'] = 'GB'; }
        else {
            $question = "Enter Country";
            $this->bakery_details['country'] = self::askForInput($question, true); }
    }

    protected function askForLanguage() {
        if (isset($this->params["language"])) {
            $this->bakery_details['language'] = $this->params["language"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['language'] = 'EN'; }
        else {
            $question = "Enter Language";
            $this->bakery_details['language'] = self::askForInput($question, true); }
    }

    protected function askForGUIMode() {
        if (isset($this->params["gui_mode"])) {
            $this->bakery_details['gui_mode'] = $this->params["gui_mode"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['gui_mode'] = 'gui'; }
        else {
            $question = "Enter GUI Mode";
            $this->bakery_details['gui_mode'] = self::askForInput($question, true); }
    }

    protected function askForNotifyDelay() {
        if (isset($this->params["notify-delay"])) {
            $this->bakery_details['notify-delay'] = $this->params["notify-delay"]; }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->bakery_details['notify-delay'] = 60; }
        else {
            $question = "Enter Notification Delay";
            $this->bakery_details['notify-delay'] = self::askForInput($question, true); }
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


    /*
     *
     * var_auth_user=vlax6i8ekjpg7ms9
var_auth_pw=0jnidiiukik2bo99
var_os=ubuntu
var_os_version=16.04.4
var_os_group=server-64bit
var_ssh_user_name=ptv
var_ssh_user_pass=ptv
var_full_user="Pharaoh Virtualize"
vm_full_name="Standard $var_os $var_os_version $var_os_group"
vm_description="This is an addition to the vanilla install of Ubuntu 14.04.2, 64Bit Architecture, Server Edition. This box contains the same configuration as that one, and also includes Virtualbox Guest Packages, PHP with some standard modules, and Pharaoh Configure."

# The Steps

# - Download an iso
# - Attach the iso to a Hardware VM and start it
# - Unattended install it
# - Add VBox guest additions and the ptv user (Not on a cloud)
# - This is the "vanilla" version of that flavour
# - Package that
# - Destroy it
# - Send it to Cloud File Storage



# Download The ISO
echo "Create OS, Version and OS Group specific directory"
mkdir -p $var_os/$var_os_version/$var_os_group
echo "Change into that directory"
cd $var_os/$var_os_version/$var_os_group
#echo "Remove anything that may be in there"
#rm -rf *
#echo "Curl download the image file"
#curl -X POST -O -J -d "control=BinaryServer&action=serve&item=$var_os&auth_user=$var_auth_user&auth_pw=$var_auth_pw&version=$var_os_version&group=$var_os_group" https://repositories.internal.pharaohtools.com/index.php
echo "Move the ISO to a generic name"
mv * ${var_os}.iso

# Should Work




# Attach ISO to VM
vmName=${var_os}_${var_os_version}
isoImage=${var_os}.iso
echo "VM Name is: $vmName"
echo "ISO Image is: $isoImage"

echo "Setting all the VM Settings and creating it"
VBoxManage unregistervm "$vmName" --delete
VBoxManage createvm --name "$vmName" --register
vmDir=$(VBoxManage showvminfo "$vmName" | grep "^Config file:"  | awk -F":" '{print $2}' | xargs -L1 -IX dirname "X")
VBoxManage modifyvm "$vmName" --memory 2048 --acpi on --boot1 disk --boot2 dvd --vram 33 --cpus 1
VBoxManage modifyvm "$vmName" --nic1 nat --nictype1 82540EM --cableconnected1 on
#VBoxManage modifyvm "$vmName" --natpf1 ",tcp,,9999,,22"
VBoxManage modifyvm "$vmName" --ostype Ubuntu_64
VBoxManage modifyvm "$vmName"  --ioapic on
VBoxManage createhd --filename "$vmDir/${vmName}.vdi" --size 80000
VBoxManage storagectl "$vmName" --name "SATA" --add sata
VBoxManage storageattach "$vmName" --storagectl "SATA" --port 0 --device 0 --type hdd --medium "${vmDir}/${vmName}.vdi"
VBoxManage storagectl "$vmName" --name "IDE" --add ide
VBoxManage storageattach "$vmName" --storagectl "IDE" --port 1 --device 0 --type dvddrive --medium "$isoImage"

#echo "Show the VM Settings"
# Start It
#VBoxManage showvminfo "$vmName"
# VBoxManage startvm "$vmName"
#VBoxManage controlvm "$vmName"  poweroff

echo "Unattended install"
echo "VBoxManage unattended install ${vmName} --iso=${isoImage} --user=${var_ssh_user_name} --password=${var_ssh_user_pass} --full-user-name=${var_full_user} --script-template=/opt/ptv_box_scripts/preseed.cfg --post-install-template=/opt/ptv_box_scripts/postinstall.sh --install-additions --locale=en_GB --country=GB --language=EN --start-vm=gui"
VBoxManage unattended install ${vmName} --iso=${isoImage} --user="${var_ssh_user_name}" --password="${var_ssh_user_pass}" --full-user-name="${var_full_user}" --script-template=/opt/ptv_box_scripts/preseed.cfg --post-install-template=/opt/ptv_box_scripts/postinstall.sh --install-additions --locale=en_GB --country=GB --language=EN --start-vm=gui
#
## wait for the installation to complete
#$comm = 'VBoxManage list runningvms' ;
#for ($i=0; $i<timeout; $i++) {
#    $out = run($comm) ;
#    if ($out includes $vmName) {
#        if (should_notify() == true) {
#            echo "Still installing" ;
#            sleep $check_delay ;
#        }
#    } else {
#        echo "Installation Complete, Guest Terminated" ;
#        break ;
#    }
#}


## Package That
#echo "Init a matching name"
#ptvirtualize init now --name=${vmName} -yg
#echo "PTV Halt it"
#ptvirtualize halt now --die-hard -yg
#echo "PTV Package it"
#ptvirtualize box package -yg \
#	--name="$vm_full_name" \
#	--vmname="$vmName" \
#	--group="ptvirtualize" \
#	--description="$vm_description" \
#	--target="/opt/ptvirtualize/boxes"
##ls -lah /opt/ptvirtualize/boxes/standard*
#
#
## Destroy That
#echo "Destroy it"
#ptvirtualize destroy now


## Send it to Cloud File Storage
#cd ..
#cd /opt/ptvirtualize/boxes/
#echo "Starting PT Repositories Upload"
#curl -F group=development -F version=${var_os_version} -F file=@/path/to/file -F control=BinaryServer -F action=serve -F item=${var_os} -F auth_user=${var_auth_user} -F auth_pw=${var_auth_pw} https://repositories.internal.pharaohtools.com/index.php

     */

}
