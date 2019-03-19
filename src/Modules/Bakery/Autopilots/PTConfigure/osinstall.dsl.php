Logging log
  log-message "Lets Bake an ISO"

Bakery osinstall
  label "The "
  iso "/home/pharaoh/Downloads/ubuntu-14.04.6-server-amd64.iso"
  name "ptv_bakery_temp_vm"
  ostype "Ubuntu_64"
  memory "512"
  vram "33"
  cpus "1"
  ssh_forwarding_port "9988"
  user_name ptv
  user_pass ptv
  full_user "Pharaoh Virtualize"
  locale en_GB
  country GB
  language EN
  gui_mode headless
  notify-delay 60
  guess

Mkdir path
  label "Ensure Temp Directory for Virtufile"
  path "/tmp/ptv_vm_osinstall"
  recursive true
  guess

RunCommand execute
  label "Initialize a matching name"
  command "cd /tmp/ptv_vm_osinstall &&  init now --name=$$vm_name -yg"
  guess

RunCommand execute
  label "PTV Halt it"
  command "cd /tmp/ptv_vm_osinstall &&  halt now --die-hard -yg"
  guess

RunCommand execute
  label "PTV Package the Virtual Machine into a Box file"
  command 'ptvirtualize box package -yg --name="$vm_full_name" --vmname="$$vm_name" --group="ptvirtualize" --description="$$vm_description" --target="/opt/ptvirtualize/boxes"'
  guess

RunCommand execute
  label "Destroy the Virtual Machine"
  command "cd /tmp/ptv_vm_osinstall &&  destroy now"
  guess
#
#RunCommand execute
#  label "Starting PT Repositories Upload"
#  command "curl -F group=development -F version=${var_os_version} -F file=@/path/to/file -F control=BinaryServer -F action=serve -F item=${var_os} -F auth_user=${var_auth_user} -F auth_pw=${var_auth_pw} https://repositories.internal.pharaohtools.com/index.php"
#  guess

Logging log
  log-message "Baking of Image Completed Packaging "
