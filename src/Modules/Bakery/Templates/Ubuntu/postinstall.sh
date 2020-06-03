#!/usr/bin/env bash
echo "Post install did run" > /opt/postinstall.diditrun
apt-get update -y
apt-get -qq install -y expect virtualbox-guest-x11 virtualbox-guest-dkms virtualbox-guest-additions-iso virtualbox-guest-utils
ln -sf /opt/VBoxGuestAdditions-*/lib/VBoxGuestAdditions /usr/lib/VBoxGuestAdditions
mkdir -p /mnt/guestadditionsiso/
mount -o loop /usr/share/virtualbox/VBoxGuestAdditions.iso /mnt/guestadditionsiso/
usermod -a -G vboxsf root
usermod -a -G vboxsf ptv
rm -rf /tmp/ga-expect.expect
echo -e '#!/usr/bin/expect\\nset timeout 180\\nspawn /mnt/guestadditionsiso/VBoxLinuxAdditions.run\\nexpect \"yes or no\"\\nsend \"yes\\r\" \\ninteract\\n' > /tmp/ga-expect.expect ;
chmod +x /tmp/ga-expect.expect
/tmp/ga-expect.expect
umount /mnt/guestadditionsiso/
rm -rf /tmp/ga-expect.expect
echo ptv ALL=NOPASSWD: ALL >> /etc/sudoers
shutdown now