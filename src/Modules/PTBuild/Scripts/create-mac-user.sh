#!/usr/bin/env bash
# Set the _www users shell to bash
chsh -s /bin/bash _www
# Create the new ptbuild user
maxid=$(dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
newid=$((maxid+1))
dscl . -create /Users/ptbuild
dscl . -create /Users/ptbuild UserShell /bin/bash
dscl . -create /Users/ptbuild RealName "Pharaoh Build"
dscl . -create /Users/ptbuild UniqueID "$newid"
dscl . -create /Users/ptbuild PrimaryGroupID 80
dscl . -create /Users/ptbuild NFSHomeDirectory /Users/ptbuild
dscl . -passwd /Users/ptbuild password
dscl . -append /Groups/admin GroupMembership ptbuild
cp -R /System/Library/User\ Template/English.lproj /Users/ptbuild
chown -R ptbuild:admin /Users/ptbuild
