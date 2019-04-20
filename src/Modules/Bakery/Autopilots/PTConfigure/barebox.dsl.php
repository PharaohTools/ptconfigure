Bakery osinstall
  label "The "
  iso "/home/pharaoh/Downloads/ubuntu-18.04.2-server-amd64.iso"
  name "$$vm_name"
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
  gui_mode gui
  notify-delay 60
  guess

Mkdir path
  label "Ensure Temp Directory for Virtufile"
  path "/tmp/ptv_vm_osinstall"
  recursive true
  guess

RunCommand execute
  label "Initialize a matching name"
  command 'cd /tmp/ptv_vm_osinstall && ptvirtualize init now --name="{{{ var::vm_name }}}" --vars="/opt/ptconfigure/ptconfigure/src/Modules/Bakery/Autopilots/PTConfigure/vars.php" -yg'
  guess

RunCommand execute
  label "PTV Halt it"
  command 'cd /tmp/ptv_vm_osinstall && ptvirtualize halt now --die-hard -yg'
  guess
  ignore_errors

RunCommand execute
  label "PTV Package the Virtual Machine into a Box file"
  command 'cd /tmp/ptv_vm_osinstall && ptvirtualize box package --name="Ubuntu 18.04.02 Server Edition 64 Bit" --vmname="ptv_bakery_temp_vm" --group="ptvirtualize" --description="vm_description" --target="/opt/ptvirtualize/boxes" -yg '
  guess

RunCommand execute
  label "Destroy the Virtual Machine"
  command "cd /tmp/ptv_vm_osinstall && ptvirtualize destroy now"
  guess
#
#RunCommand execute
#  label "Starting PT Repositories Upload"
#  command "curl -F group=server-64bit -F version=18.04.02 -F file=@/opt/ptvirtualize/boxes/ubuntu18042serveredition64bit.box -F control=BinaryServer -F action=serve -F item=ptv_ubuntu -F auth_user=${var_auth_user} -F auth_pw=${var_auth_pw} https://repositories.internal.pharaohtools.com/index.php"
#  guess

Logging log
  log-message "Baking of Image Completed Packaging "
