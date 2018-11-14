#!/usr/bin/env bash

var_auth_user=vlax6i8ekjpg7ms9
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