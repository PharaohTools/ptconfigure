#!/bin/bash
# Set the www-data users shell to bash
chsh -s /bin/bash www-data
# add the ptbuild user
useradd -m -d /home/ptbuild -s /bin/bash -c "The Pharaoh Build User" -U ptbuild