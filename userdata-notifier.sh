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

# Install application
cd /tmp
git clone https://github.com/freitasleoalves/notifier
cp /tmp/notifier/public/index.php /var/www/html/

# Executa script para configuração do composer
cd /tmp/notifier
sudo chmod +x composer-sns.sh
sudo ./composer-sns.sh

# Start Apache service
service httpd restart