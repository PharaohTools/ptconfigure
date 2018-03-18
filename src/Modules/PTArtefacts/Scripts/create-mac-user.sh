#!/usr/bin/env bash
# Set the _www users shell to bash
chsh -s /bin/bash _www

# delete any previous ptsource user
dscl . -delete /Users/ptsource
echo "Deleted any previous ptsource user"
dscl . -delete /Groups/ptsource
echo "Deleted any previous ptsource group"

echo "remove home dir"
rm -rf /Users/ptsource

# Create the new ptsource user
maxuid=$(dscl . -list /Users UniqueID | awk '{print $2}' | sort -ug | tail -1)
newuid=$((maxuid+1))
randGid=`jot -r 1  2000 65000`
# echo "new u id" $newuid
# echo "rand g id" $randGid

dscl . create /Groups/ptsource
echo $? "dscl . create /Groups/ptsource"
dscl . create /Groups/ptsource RealName "Pharaoh Source"
echo $? 'dscl . create /Groups/ptsource RealName "Pharaoh Source"'
dscl . create /Groups/ptsource gid $randGid
echo $? "dscl . create /Groups/ptsource gid $randGid"
dscl . -create /Users/ptsource
echo $? "dscl . -create /Users/ptsource"
dscl . -create /Users/ptsource UserShell /bin/bash
echo $? "dscl . -create /Users/ptsource UserShell /bin/bash"
dscl . -create /Users/ptsource RealName "Pharaoh Source"
echo $? 'dscl . -create /Users/ptsource RealName "Pharaoh Source"'
dscl . -create /Users/ptsource UniqueID $newuid
echo $? "dscl . -create /Users/ptsource UniqueID $newuid"
dscl . -create /Users/ptsource PrimaryGroupID $randGid
echo $? "dscl . -create /Users/ptsource PrimaryGroupID $randGid"
dscl . -create /Users/ptsource NFSHomeDirectory /Users/ptsource
echo $? "dscl . -create /Users/ptsource NFSHomeDirectory /Users/ptsource"
dscl . append /Groups/ptsource GroupMembership ptsource
echo $? "dscl . append /Groups/ptsource GroupMembership ptsource"

# dscl . -passwd /Users/ptsource abcdef987654321
# echo $? "dscl . -passwd /Users/ptsource abcdef987654321"

cp -R /System/Library/User\ Template/English.lproj /Users/ptsource
createhomedir -c > /dev/null
echo $? "createhomedir -c > /dev/null"

chown -R ptsource /Users/ptsource
echo $? "chown -R ptsource /Users/ptsource "