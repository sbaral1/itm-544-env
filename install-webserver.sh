#!/bin/bash

sudo apt-get install php5 git php5-cur
curl -s5 htps://getcomposer.org/installer | php
php composer-phar require aws/aws-sdk-php
sudo apt-get update -y
sudo apt-get install -y apache2
sudo apt-get install -y apache2 git php5 php5-curl mysql-client curl

git clone https://github.com/sbaral1/itm-544-env

mv ./itm-544-env/images /var/www/html/images
mv ./itm-544-env/index.html /var/www/html
mv ./itm-544-env/*.php /var/www/html

curl -sS https://getcomposer.org/installer | php

php composer.phar require aws/aws-sdk-php

mv vendor /var/www/html

echo "Hello World!" > /tmp/hello.txt
