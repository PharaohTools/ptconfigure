#!/usr/bin/env bash
# Set the _www users shell to bash
chsh -s /bin/bash _www

# delete any previous ptartefacts user
dscl . -delete /Users/ptartefacts
echo "Deleted any previous ptartefacts user"
dscl . -delete /Groups/ptartefacts
echo "Deleted any previous ptartefacts group"

echo "remove home dir"
rm -rf /Users/ptartefacts

# Create the new ptartefacts user
maxuid=$(dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
newuid=$((maxuid+1))
randGid=`jot -r 1  2000 65000`
# echo "new u id" $newuid
# echo "rand g id" $randGid

dscl . create /Groups/ptartefacts
echo $? "dscl . create /Groups/ptartefacts"
dscl . create /Groups/ptartefacts RealName "Pharaoh Source"
echo $? 'dscl . create /Groups/ptartefacts RealName "Pharaoh Source"'
dscl . create /Groups/ptartefacts gid $randGid
echo $? "dscl . create /Groups/ptartefacts gid $randGid"
dscl . -create /Users/ptartefacts
echo $? "dscl . -create /Users/ptartefacts"
dscl . -create /Users/ptartefacts UserShell /bin/bash
echo $? "dscl . -create /Users/ptartefacts UserShell /bin/bash"
dscl . -create /Users/ptartefacts RealName "Pharaoh Source"
echo $? 'dscl . -create /Users/ptartefacts RealName "Pharaoh Source"'
dscl . -create /Users/ptartefacts UniqueID $newuid
echo $? "dscl . -create /Users/ptartefacts UniqueID $newuid"
dscl . -create /Users/ptartefacts PrimaryGroupID $randGid
echo $? "dscl . -create /Users/ptartefacts PrimaryGroupID $randGid"
dscl . -create /Users/ptartefacts NFSHomeDirectory /Users/ptartefacts
echo $? "dscl . -create /Users/ptartefacts NFSHomeDirectory /Users/ptartefacts"
dscl . append /Groups/ptartefacts GroupMembership ptartefacts
echo $? "dscl . append /Groups/ptartefacts GroupMembership ptartefacts"

# dscl . -passwd /Users/ptartefacts abcdef987654321
# echo $? "dscl . -passwd /Users/ptartefacts abcdef987654321"

cp -R /System/Library/User\ Template/English.lproj /Users/ptartefacts
createhomedir -c > /dev/null
echo $? "createhomedir -c > /dev/null"

chown -R ptartefacts /Users/ptartefacts
echo $? "chown -R ptartefacts /Users/ptartefacts "