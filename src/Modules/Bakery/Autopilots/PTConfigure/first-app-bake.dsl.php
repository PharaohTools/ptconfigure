Logging log
  log-message "Lets initialise a confguration"
#
#Initialise now
#  label "Perform the Initialise"
#
#Download file
#  label 'Download the ISO file'
#  source "$$iso_file_remote_location"
#  target "/opt/ptvirtualize/$$var_os/$$var_os_version/$$var_os_group/$$var_os.$$var_os_version.$$var_os_group.iso"
#  yes
#  guess

Bakery osinstall
  iso "ubuntu.iso"
  name "ptv_bakery_temp_vm"
  ostype "Ubuntu_64"
  memory "512"
  vram "33"
  cpus "1"
  ssh_forwarding_port "9988"
#
#Bakery bake
#  ptsource_auth_user vlax6i8ekjpg7ms9
#  ptsource_auth_pw 0jnidiiukik2bo99
#  os_name ubuntu
#  os_version 16.04.4
#  os_group server-64bit
#  user_ssh_login ptv
#  user_ssh_pass ptv
#  user_full_name "Pharaoh Virtualize"
#  vm_full_name "Standard $var_os $var_os_version $var_os_group"
#  vm_description "{{{ var::::vm_description }}}"
#
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
#
#
## Send it to Cloud File Storage
#cd ..
#cd /opt/ptvirtualize/boxes/
#echo "Starting PT Repositories Upload"
#curl -F group=development -F version=${var_os_version} -F file=@/path/to/file -F control=BinaryServer -F action=serve -F item=${var_os} -F auth_user=${var_auth_user} -F auth_pw=${var_auth_pw} https://repositories.internal.pharaohtools.com/index.php

Logging log
  log-message "Configuration Bake is complete"