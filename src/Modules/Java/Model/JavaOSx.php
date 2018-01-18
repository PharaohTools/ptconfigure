<?php

Namespace Model;

class JavaOSx extends JavaUbuntu64 {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $dmgFile = BASE_TEMP_DIR."jdk-.dmg" ;
        $ray = array(
            array("command" => array( SUDOPREFIX."rm -rf $dmgFile") ),
            array("command" => array( 'curl "http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk-1.8-macosx-x64.dmg" -o "'.$dmgFile.'"') ),
            array("command" => array( SUDOPREFIX."hdiutil attach $dmgFile") ),
            array("command" => array( SUDOPREFIX.'installer -pkg /Volumes/VirtualBox/VirtualBox.pkg -target /') ),
            array("method"=> array("object" => $this, "method" => "ensureDefaultHostOnlyNetwork", "params" => array()) ),
            array("command" => array( SUDOPREFIX."hdiutil unmount /Volumes/VirtualBox/VirtualBox.pkg") ),
        ) ;
        return $ray ;
    }

    protected function runJavaInstall() {
        $is_java_installed_command = "bash -c '. /etc/profile ; java -version;' 2>&1" ;
        $is_java_installed_out = $this->executeAndLoad($is_java_installed_command) ;
        $str_to_find = 'java version' ;
        if (substr_count($is_java_installed_out, $str_to_find) == 1 ) {
            $is_java_installed = true ;
        } else {
            $is_java_installed = false ;
        }

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if ($is_java_installed === true) {
            $str_two_to_find = 'build '.$this->javaDetails['version_short'] ;
            if (substr_count($is_java_installed_out, $str_two_to_find) == 1 ) {
                $requested_version_is_installed = true ;
            } else {
                $msg =
                    "A Different Java JDK Version than the requested {$this->javaDetails['version_short']} has ben found." ;
                $logging->log($msg, $this->getModuleName()) ;
                $requested_version_is_installed = false ;
            }
        } else {
            $msg =
                "No Java JDK installation has ben found." ;
            $logging->log($msg, $this->getModuleName()) ;
            $requested_version_is_installed = false ;
        }
        $force_param_is_set = (isset($this->params["force"]) && $this->params["force"] != false ) ;
        if ($requested_version_is_installed && !$force_param_is_set) {
            $msg =
                "Requested Java JDK Version {$this->javaDetails['version_short']} is already installed." .
                " Use force parameter to install anyway." ;
            $logging->log($msg, $this->getModuleName()) ;
            $ray = array( ) ;

        } else {

            if ($force_param_is_set && $is_java_installed != "") {
                $msg = "Found $is_java_installed version already installed, though installing anyway as force param is set." ;
                $logging->log($msg, $this->getModuleName()) ;
            }

            $tmp_java = "/tmp/oraclejdk{$this->javaDetails['version_short']}.tar.gz" ;
            if (!file_exists($tmp_java)) {
                $this->packageDownload($this->javaDetails['jdk_url'], $tmp_java) ;
            }

            $dmgFile = BASE_TEMP_DIR."jdk-.dmg" ;
            $ray = array(
                array("command" => array( SUDOPREFIX."rm -rf $dmgFile") ),
                array("command" => array( 'curl "http://46f95a86014936ec1625-77a12a9c8b6f69dd83500dbd082befcc.r16.cf3.rackcdn.com/jdk-1.8-macosx-x64.dmg" -o "'.$dmgFile.'"') ),
                array("command" => array( SUDOPREFIX."hdiutil attach $dmgFile") ),
                array("command" => array( SUDOPREFIX.'installer -pkg /Volumes/VirtualBox/VirtualBox.pkg -target /') ),
                array("method"=> array("object" => $this, "method" => "ensureDefaultHostOnlyNetwork", "params" => array()) ),
                array("command" => array( SUDOPREFIX."hdiutil unmount /Volumes/VirtualBox/VirtualBox.pkg") ),
            ) ;

            $this->installCommands = $ray ;
            return $this->doInstallCommand() ;

            $ray =
                array(
                    array("command" => array(
//                        "if [ ! -f /tmp/oraclejdk{$this->javaDetails['version_short']}.tar.gz ] ; then curl -o /tmp/oraclejdk{$this->javaDetails['version_short']}.tar.gz {$this->javaDetails['jdk_url']} ; fi" ,
                        "mkdir -p /tmp/oraclejdk{$this->javaDetails['version_short']}",
                        "tar -xzf /tmp/oraclejdk{$this->javaDetails['version_short']}.tar.gz -C /tmp/oraclejdk{$this->javaDetails['version_short']}",
                        "rm -f /tmp/oraclejdk{$this->javaDetails['version_short']}.tar.gz",
                        "mkdir -p ****PROGDIR****" ,
                        "rm -rf ****PROGDIR****/*" ,
//                    "apt-get install libc6-i386" ,
                        "cp -r /tmp/oraclejdk{$this->javaDetails['version_short']}/{$this->javaDetails['extracted_dir']}/* ****PROGDIR****" ,
                        "rm -rf /tmp/oraclejdk{$this->javaDetails['version_short']}" ,
                        "cd ****PROGDIR****",
                        "chmod a+x ****PROGDIR****",
                        'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/profile',
                        'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/profile',
                        'echo \'export JAVA_HOME\' >> /etc/profile',
                        'echo \'export PATH\' >> /etc/profile',
                        'echo \'JAVA_HOME=****PROGDIR****\' >> /etc/bash.bashrc',
                        'echo \'PATH=$PATH:$HOME/bin:$JAVA_HOME/bin\' >> /etc/bash.bashrc',
                        'echo \'export JAVA_HOME\' >> /etc/bash.bashrc',
                        'echo \'export PATH\' >> /etc/bash.bashrc',
                        SUDOPREFIX.'update-alternatives --install "/usr/bin/java" "java" "****PROGDIR****/bin/java" 1 ',
                        SUDOPREFIX.'update-alternatives --install "/usr/bin/javac" "javac" "****PROGDIR****/bin/javac" 1 ',
                        SUDOPREFIX.'update-alternatives --install "/usr/bin/javaws" "javaws" "****PROGDIR****/bin/javaws" 1 ',
                        SUDOPREFIX.'update-alternatives --set java ****PROGDIR****/bin/java ',
                        SUDOPREFIX.'update-alternatives --set javac ****PROGDIR****/bin/javac ',
                        SUDOPREFIX.'update-alternatives --set javaws ****PROGDIR****/bin/javaws ',
//                        '. /etc/profile'
                    )   )
                ) ;
        }
    }


    public function getJavaDetails($version) {
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

    public function versionInstalledCommandTrimmer($text) {
        $leftQuote = strpos($text, 'java version "') + 14 ;
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
