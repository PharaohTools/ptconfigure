RunCommand install
  label "Stop this Autopilot if the run type parameter is not set"
  guess
  command "exit 1"
  not_when "{{{ Param::run_type }}}"

EnvironmentConfig configure
  label "Lets ensure an environment in our papyrusfile for our $$env_id_slug environment"
  guess true
  environment-name "$$env_id_slug"
  tmp-dir "/tmp/"
  keep-current-environments true
  no-manual-servers true
  add-single-environment true
  when "$$run_type_add"

Boxify box-ensure
  label "Ask $$current_cloud_provider API to create our Single Server node for our $$env_id_slug environment"
  guess true
  environment-name "$$env_id_slug"
  provider-name "$$current_cloud_provider"
  box-amount "$$current_cloud_box_amount"
  image-id "$$current_cloud_image_id"
  region-id "$$current_cloud_region_id"
  size-id "$$current_cloud_size_id"
  server-prefix ""
  box-user-name "root"
  key-path "KS::id_rsa"
  ssh-key-name "$$cloud_ssh_key_name"
  wait-for-box-info true
  wait-until-active true
  when "$$run_type_add"

Boxify box-destroy
  label "Ask Provider API to destroy our node"
  guess true
  destroy-all-boxes
  environment-name "$$env_id_slug"
  provider-name "$$current_cloud_provider"
  when "$$run_type_remove"

EnvironmentConfig del
  label "Lets remove our $$env_id_slug environment from our papyrusfile"
  guess true
  environment-name "$$env_id_slug"
  when "$$run_type_remove"
