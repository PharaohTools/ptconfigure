#!/bin/sh

# Change this line to the URI path of the xcode DMG file.
XCODE_PATH="/ios/ios_sdk_4.2__final/xcode_3.2.5_and_ios_sdk_4.2_final.dmg"


USERNAME=
PASSWORD=

curl \
        -L -s -k \
        --cookie-jar cookies \
        -A "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5" \
        https://developer.apple.com/devcenter/ios/login.action \
        -o login.html

ACTION=$(sed -n 's/.*action="\(.*\)".*/\1/p' login.html)
WOSID=$(sed -n 's/.*wosid" value="\(.*\)".*/\1/p' login.html)
echo "action=${ACTION}"
echo "wosid=${WOSID}"

curl \
        -s -k --cookie-jar cookies --cookie cookies \
        -A "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5" \
        -e ";auto" "https://daw.apple.com${ACTION}?theAccountName=${USERNAME}&theAccountPW=${PASSWORD}&theAuxValue=&wosid=${WOSID}&1.Continue.x=0&1.Continue.y=0" \
        > /dev/null

curl \
        -L --cookie-jar cookies --cookie cookies \
        -A "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5" \
        -O https://developer.apple.com/ios/download.action?path=${XCODE_PATH}

rm login.html
rm cookies