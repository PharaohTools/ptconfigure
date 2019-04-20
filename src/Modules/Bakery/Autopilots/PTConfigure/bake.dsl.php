Logging log
  log-message "Lets initialise a configuration"

Bakery bake
  virtufile "ubuntu.iso"
  name "ptv_bakery_temp_vm"
  ostype "Ubuntu_64"
  memory "512"
  vram "33"
  cpus "1"
  ssh_forwarding_port "9988"

Logging log
  log-message "Configuration Bake is complete"