#!/usr/bin/env bash
cd vanubu
ptvirtualize up now --mod --pro
ptvirtualize halt now --die-hard
ptvirtualize box package -yg \
	--name="Standard Ubuntu 14.04.2 64 bit Server Edition" \
	--vmname="vanillaubuntu1404264bitserveredition" \
	--group="ptvirtualize" \
	--description="This is an addition to the vanilla install of Ubuntu 14.04.2, 64Bit Architecture, Server Edition. This box contains the same configuration as that one, and also includes Virtualbox Guest Packages, PHP with some standard modules, and Pharaoh Configure." \
	--target="/opt/ptvirtualize/boxes"
ls -lah /opt/ptvirtualize/boxes/standard*
ptvirtualize destroy now

cd ..
cd /opt/ptvirtualize/boxes/
echo "Starting Rax Upload"
rack files object upload --container phlagrant-boxes --name standardubuntu1404264bitserveredition.box --file standardubuntu1404264bitserveredition.box