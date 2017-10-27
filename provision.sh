#!/bin/bash

yum install -y epel-release
yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
yum install -y --enablerepo=remi-php56 php php-cli php-common php-devel

\cp -f /usr/share/zoneinfo/Japan /etc/localtime
\cp -f /etc/selinux/config /etc/selinux/config_org
sed -i -e "s/SELINUX=enforcing/SELINUX=disabled/" /etc/selinux/config
setenforce 0

cd /source/
curl -sS https://getcomposer.org/installer | php
php composer.phar install

echo "provisioning success"

