#!/usr/bin/env bash
# Set the _www users shell to bash
chsh -s /bin/bash _www



# delete any previous ptbuild user
dscl . -delete /Users/ptbuild
echo "Deleted any previous ptbuild user"
dscl . -delete /Groups/ptbuild
echo "Deleted any previous ptbuild group"

echo "remove home dir"
rm -rf /Users/ptbuild

# Create the new ptbuild user
maxuid=$(dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
newuid=$((maxuid+1))
randGid=`jot -r 1  2000 65000`
echo "new u id" $newuid
echo "rand g id" $randGid

dscl . create /Groups/ptbuild
echo $? "dscl . create /Groups/ptbuild"
dscl . create /Groups/ptbuild RealName "Pharaoh Build"
echo $? 'dscl . create /Groups/ptbuild RealName "Pharaoh Build"'
dscl . create /Groups/ptbuild gid $randGid
echo $? "dscl . create /Groups/ptbuild gid $randGid"
dscl . -create /Users/ptbuild
echo $? "dscl . -create /Users/ptbuild"
dscl . -create /Users/ptbuild UserShell /bin/bash
echo $? "dscl . -create /Users/ptbuild UserShell /bin/bash"
dscl . -create /Users/ptbuild RealName "Pharaoh Build"
echo $? 'dscl . -create /Users/ptbuild RealName "Pharaoh Build"'
dscl . -create /Users/ptbuild UniqueID $newuid
echo $? "dscl . -create /Users/ptbuild UniqueID $newuid"
dscl . -create /Users/ptbuild PrimaryGroupID $randGid
echo $? "dscl . -create /Users/ptbuild PrimaryGroupID $randGid"
dscl . -create /Users/ptbuild NFSHomeDirectory /Users/ptbuild
echo $? "dscl . -create /Users/ptbuild NFSHomeDirectory /Users/ptbuild"
dscl . append /Groups/ptbuild GroupMembership ptbuild
echo $? "dscl . append /Groups/ptbuild GroupMembership ptbuild"

dscl . -passwd /Users/ptbuild abcdef987654321
echo $? "dscl . -passwd /Users/ptbuild abcdef987654321"

cp -R /System/Library/User\ Template/English.lproj /Users/ptbuild
createhomedir -c > /dev/null
echo $? "createhomedir -c > /dev/null"

chown -R ptbuild /Users/ptbuild
echo $? "chown -R ptbuild /Users/ptbuild "