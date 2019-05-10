#!/usr/bin/env bash
apt-get update -y
apt-get install -y virtualbox-guest-x11 virtualbox-guest-dkms virtualbox-guest-additions-iso
mkdir -p /mnt/guestadditionsiso/
mount -o loop /usr/share/virtualbox/VBoxGuestAdditions.iso /mnt/guestadditionsiso/
/mnt/guestadditionsiso/VBoxLinuxAdditions.run
umount /mnt/guestadditionsiso/
shutdown now