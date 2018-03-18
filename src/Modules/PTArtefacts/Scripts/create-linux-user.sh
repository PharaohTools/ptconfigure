#!/bin/bash
# Set the www-data users shell to bash
# chsh -s /bin/bash www-data
# add the ptartefacts user
useradd -m -d /home/ptartefacts -s /bin/bash -c "Pharaoh Source User" -U ptartefacts