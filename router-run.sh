#!/bin/bash
# set -x

export app_prefix='double-travel'

ENABLE_XDEBUG="-d xdebug.remote_autostart=1 -d xdebug.remote_enable=1"
# if you have a problem with this port - try turning off your skype application
php -S localhost:80 $ENABLE_XDEBUG -t public login.php
