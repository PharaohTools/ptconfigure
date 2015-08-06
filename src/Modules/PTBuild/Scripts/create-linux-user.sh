#!/bin/bash
useradd ptbuild -g
useradd -d /home/ptbuild -m ptbuild
useradd -m -d /home/ptbuild -s /bin/bash -c "The Pharaoh Build User" -U ptbuild