#!/usr/bin/env bash
apt-get update -y
apt-get install -y virtualbox-guest-additions-iso
mkdir -p /mnt/guestadditionsiso/
mount -o loop /usr/share/virtualbox/VBoxGuestAdditions.iso /mnt/guestadditionsiso/
/mnt/guestadditionsiso/VBoxLinuxAdditions.run
unmount /mnt/guestadditionsiso/
shutdown now