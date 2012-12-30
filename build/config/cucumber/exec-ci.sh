/bin/bash --login
PATH=$PATH:$HOME/.rvm/bin # Add RVM to PATH for scripting
export PATH
[[ -s "$HOME/.rvm/scripts/rvm" ]] && source "$HOME/.rvm/scripts/rvm"
rvm use 1.9.3
cucumber --format json -o cucumber.json > /tmp/jsonfile