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

    protected $bakeryDetails ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Bakery";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForBakeryInstallVersion", "params" => array()) ),
//            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "runBakeryInstall", "params" => array()) ),
        );
        //@todo uninstall commands of bakery
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForBakeryInstallDirectory", "params" => array()) ),);
        $this->programNameMachine = "bakery"; // command and app dir name
        $this->programNameFriendly = "!!Bakery JDK!!"; // 12 chars
        $this->programNameInstaller = "The Oracle Bakery JDK";
        $this->statusCommand = 'bakery -version' ;
        $this->versionInstalledCommand = 'bakery -version 2>&1' ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy bakery" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy bakery" ;
        $this->initialize();
    }

    protected function askForBakeryInstallVersion() {
        if (isset($this->params["version"])) {
            $this->params["bakery-install-version"] = $this->params["version"] ;
            $this->bakeryDetails = $this->getBakeryDetails($this->params["version"]);
            $this->programDataFolder = "/var/lib/jvm/jdk".$this->params["version"] ;
            return ;  }
        else if (isset($this->params["bakery-install-version"])) {
            $this->bakeryDetails = $this->getBakeryDetails($this->params["bakery-install-version"]); }
        else if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            $this->params["bakery-install-version"] = "1.7" ;
            $this->bakeryDetails = $this->getBakeryDetails("1.7") ; }
        else {
            $question = "Enter Bakery Install Version (1.7 or 1.8):";
            $jd = self::askForInput($question, true);
            $this->params["bakery-install-version"] = $jd ;
            $this->bakeryDetails = $this->getBakeryDetails($jd); }
        $this->programDataFolder = "/var/lib/jvm/jdk".$this->params["bakery-install-version"];
    }

    protected function runBakeryInstall() {
        $is_bakery_installed_command = "bash -c '. /etc/profile ; bakery -version;' 2>&1" ;
        $is_bakery_installed_out = $this->executeAndLoad($is_bakery_installed_command) ;
        $str_to_find = 'bakery version' ;
        if (substr_count($is_bakery_installed_out, $str_to_find) == 1 ) {
            $is_bakery_installed = true ;
        } else {
            $is_bakery_installed = false ;
        }

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if ($is_bakery_installed === true) {
            $str_two_to_find = 'build '.$this->bakeryDetails['version_short'] ;
            if (substr_count($is_bakery_installed_out, $str_two_to_find) == 1 ) {
                $requested_version_is_installed = true ;
            } else {
                $msg =
                    "A Different Bakery JDK Version than the requested {$this->bakeryDetails['version_short']} has ben found." ;
                $logging->log($msg, $this->getModuleName()) ;
                $requested_version_is_installed = false ;
            }
        } else {
            $msg =
                "No Bakery JDK installation has ben found." ;
            $logging->log($msg, $this->getModuleName()) ;
            $requested_version_is_installed = false ;
        }
        $force_param_is_set = (isset($this->params["force"]) && $this->params["force"] != false ) ;
        if ($requested_version_is_installed && !$force_param_is_set) {
            $msg =
                "Requested Bakery JDK Version {$this->bakeryDetails['version_short']} is already installed." .
                " Use force parameter to install anyway." ;
            $logging->log($msg, $this->getModuleName()) ;
            $ray = array( ) ;

        } else {

            if ($force_param_is_set && $is_bakery_installed != "") {
                $msg = "Found $is_bakery_installed version already installed, though installing anyway as force param is set." ;
                $logging->log($msg, $this->getModuleName()) ;
            }

            $stamp = time() ;
            $tmp_bakery = "/tmp/oraclejdk{$stamp}.tar.gz" ;
            if (!file_exists($tmp_bakery)) {
                $this->packageDownload($this->bakeryDetails['jdk_url'], $tmp_bakery) ;
            }

            $tmp_str = "/tmp/oraclejdk{$stamp}" ;

            mkdir($tmp_str, 0775) ;

            // decompress from gz
            $p = new \PharData($tmp_str.'.tar.gz');
            $p->decompress(); // creates /path/to/my.tar

            // unarchive from the tar
            $phar = new \PharData($tmp_str.'.tar');
            $phar->extractTo($tmp_str, null, true);

            unlink($tmp_str.'.tar.gz') ;

            if (!is_dir($this->programDataFolder)) {
                mkdir($this->programDataFolder, 0775, true) ;
            }

            $comm = "rm -rf {$this->programDataFolder}" ;
            $this->executeAndOutput($comm) ;

            // MAKE IT RECURSIVE
            $comm = 'cp -r '.$tmp_str.DIRECTORY_SEPARATOR."{$this->bakeryDetails['extracted_dir']} {$this->programDataFolder}" ;
            $this->executeAndOutput($comm) ;

            chmod($tmp_str, octdec('0711') ) ;

            $profile_lines = array(
                'echo \'JAVA_HOME='.$this->programDataFolder.'\' >> /etc/profile',
                'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/profile',
                'echo \'export JAVA_HOME\' >> /etc/profile',
                'echo \'export PATH\' >> /etc/profile',
            ) ;

            foreach ($profile_lines as $profile_line) {
                $this->executeAndOutput($profile_line) ;
            }

            $j_opts = array('bakery', 'bakeryc', 'bakeryws') ;
            foreach ($j_opts as $j_opt) {
                $comm = SUDOPREFIX.'update-alternatives --install "/usr/bin/'.$j_opt.'" "'.$j_opt.'" "'.$this->programDataFolder.'/bin/'.$j_opt.'" 1 ' ;
                $this->executeAndOutput($comm) ;
            }

            foreach ($j_opts as $j_opt) {
                $comm = SUDOPREFIX.'update-alternatives --set '.$j_opt.' '.$this->programDataFolder.'/bin/'.$j_opt.' ' ;
                $this->executeAndOutput($comm) ;
            }

        }
//        $this->installCommands = $ray ;
//        return $this->doInstallCommand() ;
        return true ;
    }

    public function fsmodify($obj) {
        $chunks = explode(DIRECTORY_SEPARATOR, $obj);
        chmod($obj, is_dir($obj) ? 0755 : 0644);
        chown($obj, $chunks[2]);
        chgrp($obj, $chunks[2]);
    }


    public function fsmodifyr($dir) {
        if($objs = glob($dir.DIRECTORY_SEPARATOR."*")) {
            foreach($objs as $obj) {
                $this->fsmodify($obj);
                if(is_dir($obj)) $this->fsmodifyr($obj);
            }
        }
        return $this->fsmodify($dir);
    }


    public function packageDownload($remote_source, $temp_exe_file) {
        if (file_exists($temp_exe_file)) {
            unlink($temp_exe_file) ;
        }
        # var_dump('BWA packageDownload 2', $_ENV, $temp_exe_file) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Downloading From {$remote_source}", $this->getModuleName() ) ;

        echo "Download Starting ...".PHP_EOL;
        ob_start();
        ob_flush();
        flush();

        $fp = fopen ($temp_exe_file, 'w') ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_source);
        // curl_setopt($ch, CURLOPT_BUFFERSIZE,128);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, 'progress'));
        curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        # $error = curl_error($ch) ;
        # var_dump('downloaded', $downloaded, $error) ;
        curl_close($ch);

        ob_flush();
        flush();

        echo "Done".PHP_EOL ;
        return $temp_exe_file ;
    }

    public function progress($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        $is_quiet = (isset($this->params['quiet']) && ($this->params['quiet'] == true) ) ;
        if ($is_quiet == false) {
            if($download_size > 0) {
                $dl = ($downloaded / $download_size)  * 100 ;
                # var_dump('downloaded', $dl) ;
                $perc = round($dl, 2) ;
                # var_dump('perc', $perc) ;
                echo "{$perc} % \r" ;
            }
            ob_flush();
            flush();
        }
    }

    public function getBakeryDetails($version) {
        if ($version == "1.8") {
            $details['jdk_url'] = "http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk1.8x64.tar.gz" ;
            $details['path_in_repo'] = "phpengine-cleo-jdk-64-6c383e2868bd/jdk-7u60-linux-x64.tar.gz" ;
            $details['fname_in_repo'] = "jdk-7u60-linux-x64.tar.gz" ;
            $details['version_short'] = "1.8.0" ;
            $details['extracted_dir'] = "jdk{$details['version_short']}_144" ;
        } else {
            $details['jdk_url'] = "http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk1.7.tar.gz" ;
            $details['path_in_repo'] = "jdk-7u60-linux-x64.tar.gz" ;
            $details['fname_in_repo'] = "jdk-7u60-linux-x64.tar.gz" ;
            $details['version_short'] = "1.7.0" ;
            $details['extracted_dir'] = "jdk{$details['version_short']}_60" ;
        }
        return $details ;
    }

    protected function askForBakeryInstallDirectory() {
        if (isset($this->params["guess"]) && $this->params["guess"]==true) {
            return; }
        else if (isset($this->params["bakery-install-dir"])) {
            $this->programDataFolder = $this->params["bakery-install-dir"]; }
        else {
            $question = "Enter Bakery Install Directory (no trailing slash):";
            $this->programDataFolder = self::askForArrayOption($question, array("1.7", "1.8"), true); }
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
