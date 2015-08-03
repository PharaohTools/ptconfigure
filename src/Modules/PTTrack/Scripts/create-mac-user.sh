#!/usr/bin/env bash
# Set the _www users shell to bash
chsh -s /bin/bash _www
# Create the new pttrack user
maxid=$(dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
newid=$((maxid+1))
dscl . -create /Users/pttrack
dscl . -create /Users/pttrack UserShell /bin/bash
dscl . -create /Users/pttrack RealName "Pharaoh Track"
dscl . -create /Users/pttrack UniqueID "$newid"
dscl . -create /Users/pttrack PrimaryGroupID 80
dscl . -create /Users/pttrack NFSHomeDirectory /Users/pttrack
dscl . -passwd /Users/pttrack password
dscl . -append /Groups/admin GroupMembership pttrack
cp -R /System/Library/User\ Template/English.lproj /Users/pttrack
chown -R pttrack:admin /Users/pttrack
