#!/bin/bash

yum install -y git unzip

yum install -y epel-release
yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
yum install -y --enablerepo=remi-php71 php php-cli php-common php-devel php-xml php-mbstring

\cp -f /usr/share/zoneinfo/Japan /etc/localtime
\cp -f /etc/selinux/config /etc/selinux/config_org
sed -i -e "s/SELINUX=enforcing/SELINUX=disabled/" /etc/selinux/config
setenforce 0

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

cd /source/
composer install

echo "provisioning success"

