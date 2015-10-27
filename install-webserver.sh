#!/bin/bash

sudo apt-get install php5 git php5-cur
curl -s5 htps://getcomposer.org/installer | php
php composer-phar require aws/aws-sdk-php
sudo apt-get update -y
sudo apt-get install -y apache2


echo "Hello World!" > /tmp/hello.txt
