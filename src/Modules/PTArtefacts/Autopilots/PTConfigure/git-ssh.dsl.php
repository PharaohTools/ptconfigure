Logging log
  log-message "Lets configure A Git SSH Server and User for Pharaoh Source"

RunCommand execute
  label 'Install Openssh'
  guess
  command "apt-get install openssh-server -y"
  when "{{{ Param::enable-ssh }}}"
  ignore_errors

RunCommand execute
  label 'Install Openssh'
  guess
  command "yum install openssh-server -y"
  when "{{{ Param::enable-ssh }}}"
  ignore_errors

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
  label 'Add A Random Password to the Pharaoh Tools Git User'
  guess
  command "usermod -p `date +%s | sha256sum | base64 | head -c 32 ; echo` ptgit"
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label 'Add the Pharaoh Tools Git User to the Pharaoh Source User Group'
  guess
  command "usermod -a -G ptartefacts ptgit"
  when "{{{ Param::enable-ssh }}}"

Mkdir path
  label "Ensure the Git user script directory exists"
  path "/home/ptgit/ptartefacts/"
  when "{{{ Param::enable-ssh }}}"

Copy put
  label 'Copy in our Git Bash Wrapper Script'
  guess
  source "/opt/ptartefacts/ptartefacts/src/Modules/GitServer/Scripts/openssh_wrap_git.bash"
  target "/home/ptgit/ptartefacts/"
  when "{{{ Param::enable-ssh }}}"

Copy put
  label 'Copy in our Pharaoh Source Repository Auth Script'
  guess
  source "/opt/ptartefacts/ptartefacts/src/Modules/GitServer/Scripts/openssh_auth.php"
  target "/home/ptgit/ptartefacts/"
  when "{{{ Param::enable-ssh }}}"

Chown path
  label "Copy in the auth script to the correct place /PTArtefactsScripts/ at root"
  path "/home/ptgit/ptartefacts"
  recursive true
  user ptgit
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label "Remove the SSH Auth script directory"
  guess
  command "rm -rf /PTArtefactsScripts/"
  when "{{{ Param::disable-ssh }}}"

Mkdir path
  label "Ensure the SSH Auth script directory exists"
  path "/PTArtefactsScripts/"
  when "{{{ Param::enable-ssh }}}"

Copy put
  label 'Copy in our Git Key Auth Script'
  guess
  source "/opt/ptartefacts/ptartefacts/src/Modules/GitServer/Scripts/openssh_find_keys.php"
  target "/PTArtefactsScripts/"
  when "{{{ Param::enable-ssh }}}"

Chmod path
  label "Set mode for ptartefacts scripts"
  path "/PTArtefactsScripts/"
  recursive true
  mode 0600
  when "{{{ Param::enable-ssh }}}"

Chown path
  label "Set owner for ptartefacts scripts"
  path "/PTArtefactsScripts/"
  recursive true
  user root
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label "Make Auth script executable"
  guess
  command "chmod -R +x /PTArtefactsScripts/"
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label "Create a Copy of the sshd_config if one does not exist"
  guess
  command "cp /etc/ssh/sshd_config /etc/ssh/sshd_config_ptartefacts"
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search "Match User ptgit"
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search '  PasswordAuthentication no'
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search '  AuthorizedKeysCommand /PTArtefactsScripts/openssh_find_keys.php %u %f'
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Add PT Config to SSH for user"
  file "/etc/ssh/sshd_config"
  search '  AuthorizedKeysCommandUser root'
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Allow Sudo From PTArtefacts To PTGit"
  file "/etc/sudoers"
  search 'ptartefacts ALL=(ptgit) NOPASSWD:/usr/bin/chown'
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Allow Sudo From PTArtefacts To PTGit"
  file "/etc/sudoers"
  search 'ptartefacts ALL=(ptgit) NOPASSWD:/usr/bin/chgrp'
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Allow Sudo From PTArtefacts To PTGit"
  file "/etc/sudoers"
  search 'ptartefacts ALL=(ptgit) NOPASSWD:/bin/chown'
  when "{{{ Param::enable-ssh }}}"

File should-have-line
  label "Allow Sudo From PTArtefacts To PTGit"
  file "/etc/sudoers"
  search 'ptartefacts ALL=(ptgit) NOPASSWD:/bin/mkdir'
  when "{{{ Param::enable-ssh }}}"

RunCommand execute
  label "Restart SSH for New Git Settings"
  guess
  command "service ssh restart"
  when "{{{ Param::enable-ssh }}}"

Logging log
  log-message "Configuration Management for A Git SSH Server and User for Pharaoh Source Complete"