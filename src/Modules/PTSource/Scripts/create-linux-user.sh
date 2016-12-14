#!/bin/bash
# Set the www-data users shell to bash
# chsh -s /bin/bash www-data
# add the ptsource user
useradd -m -d /home/ptsource -s /bin/bash -c "Pharaoh Source User" -U ptsource