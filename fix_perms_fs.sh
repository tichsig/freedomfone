#!/bin/bash 
#Fix permissions in the Scripts and XML_CURL areas
chmod 755 /opt/freeswitch/
find /opt/freeswitch/scripts -type f | grep -v '\.svn' | xargs chown freeswitch:daemon
find /opt/freeswitch/scripts -type f | grep -v '\.svn' | xargs chmod 0664
find /opt/freeswitch/scripts -type f | grep -v '\.svn' | xargs chmod g-s
find /opt/freeswitch/scripts -type d | grep -v '\.svn' | xargs chown freeswitch:daemon
find /opt/freeswitch/scripts -type d | grep -v '\.svn' | xargs chmod 0775
find /opt/freeswitch/scripts -type d | grep -v '\.svn' | xargs chmod g-s

#We do not have JS to be visible via the GUI
find /opt/freeswitch/scripts -type f -name main.js  | grep -v '\.svn' | xargs  chmod 600
find /opt/freeswitch/scripts -type f -name "*mp3"  | grep -v '\.svn' | xargs  chown freeswitch:www-data
find /opt/freeswitch/scripts -type f -name "*wav"  | grep -v '\.svn' | xargs  chown freeswitch:www-data 

find  /opt/freeswitch/scripts/ -type d | xargs chmod 775 
find  /opt/freeswitch/scripts/ -type f | xargs chmod 664 
chown -R freeswitch:www-data /opt/freeswitch/scripts/freedomfone/leave_message/1*/conf
chown www-data:www-data /opt/freeswitch/scripts/freedomfone/leave_message/1*/audio_menu
chown www-data:www-data /opt/freeswitch/scripts/freedomfone/ivr/nodes
chown www-data:www-data /opt/freeswitch/scripts/freedomfone/ivr/1*/ivr
chown www-data:www-data /opt/freeswitch/scripts/freedomfone/ivr/1*/conf
chown -Rf www-data:www-data /opt/freedomfone/xml_curl
