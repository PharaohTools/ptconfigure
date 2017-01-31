Logging log
  log-message "Lets configure A Git SSH Server and User for Pharaoh Source"

RunCommand execute
  label 'Install Openssh'
  guess
  command "apt-get install openssh-server -y"

RunCommand execute
  label 'Delete the Pharaoh Tools Git User (If existing)'
  guess
  command "userdel ptgit || true"

RunCommand execute
  label 'Add A Pharaoh Tools Git User'
  guess
  command "useradd -m -d /home/ptgit -s /bin/bash ptgit"
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label 'Add A Password to the Pharaoh Tools Git User'
  guess
  command "usermod -p `date +%s | sha256sum | base64 | head -c 32 ; echo` ptgit"
  when "{{{ Param::enable-ssh }}}"

Mkdir path
  label "Ensure the Git user script directory exists"
  path "/home/ptgit/ptsource/"
  when "{{{ Param::enable-ssh }}}"

Copy put
  label 'Copy in our Git Bash Wrapper Script'
  guess
  source "/opt/ptsource/ptsource/src/Modules/GitServer/Scripts/openssh_wrap_git.bash"
  target "/home/ptgit/ptsource/"
  when "{{{ Param::enable-ssh }}}"

Copy put
  label 'Copy in our Pharaoh Source Repository Auth Script'
  guess
  source "/opt/ptsource/ptsource/src/Modules/GitServer/Scripts/openssh_auth.php"
  target "/home/ptgit/ptsource/"
  when "{{{ Param::enable-ssh }}}"

# /home/ptgit/ptsource
# make the above dir
# then copy in wrap_git.bash and openssh_auth.ph
# chown to ptgit user

Chown path
  label "Copy in the auth script to the correct place /PTSourceScripts/ at root"
  path "/home/ptgit/ptsource"
  recursive true
  user ptgit

Mkdir path
  label "Ensure the SSH Auth script directory exists"
  path "/PTSourceScripts/"
  when "{{{ Param::enable-ssh }}}"

Copy put
  label 'Copy in our Git Key Auth Script'
  guess
  source "/opt/ptsource/ptsource/src/Modules/GitServer/Scripts/openssh_find_keys.php"
  target "/PTSourceScripts/"
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label "Make Auth script executable"
  command "chmod +x /PTSourceScripts/openssh_find_keys.php"
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label "Remove the SSH Auth script directory"
  command "rm -rf /PTSourceScripts/"
  when "{{{ Param::disable-ssh }}}"

Chmod path
  label "Set mode for ptsource scripts"
  path "/PTSourceScripts/"
  recursive true
  mode 0600

Chown path
  label "Set owner for ptsource scripts"
  path "/PTSourceScripts/"
  recursive true
  user root

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search "Match User ptgit"

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search '  PasswordAuthentication no'

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search '  AuthorizedKeysCommand /PTSourceScripts/openssh_find_keys.php %u %k'

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search '  AuthorizedKeysCommandUser root'

RunCommand install
  guess
  command "service ssh restart"
  when "{{{ Param::enable-ssh }}}"

Logging log
  log-message "Configuration Management for A Git SSH Server and User for Pharaoh Source Complete"