#!/bin/bash

# Update with latest packages
yum update -y

# Install Apache
yum install -y httpd mysql php php-mysql php-mysqlnd php-pdo telnet tree git

# Enable Apache service to start after reboot
sudo systemctl enable httpd

# Config connect to DB
cat <<EOT >> /var/www/config.php
<?php

define('DB_SERVER', 'rds-db-notifier.cbwu5vcv2r7m.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'adminpwd');
define('DB_DATABASE', 'notifier');

?>
EOT

# Config composer SNS
cd /home
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup. php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
composer require aws/aws-sdk-php

# Install application
cd /tmp
git clone https://github.com/freitasleoalves/notifier
cp /tmp/notifier/public/index.php /var/www/html/

# Start Apache service
service httpd restart