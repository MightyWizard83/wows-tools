##Ubuntu 16.04
https://tecadmin.net/install-laravel-framework-on-ubuntu/

sudo apt-get update
sudo apt-get upgrade

##Step 1 – Install LAMP

sudo apt-get install python-software-properties
sudo apt-get install software-properties-common
sudo apt-get install -y language-pack-en-base
sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

sudo apt-get update
sudo apt-get install -y php7.2 php7.2-gd php7.2-mbstring php7.2-xml

sudo apt-get install apache2 libapache2-mod-php7.2

sudo apt-get install mysql-server php7.2-mysql

##Install mcrypt
sudo apt-get -y install gcc make autoconf libc-dev pkg-config
sudo apt-get -y install php7.2-dev
sudo apt-get -y install libmcrypt-dev

sudo pecl install mcrypt-1.0.1

##Install Composer

sudo apt-get install curl
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer


##Install GIT
apt-get update
apt-get install git-core

##Install Laravel
cd /var/www/
git clone https://github.com/MightyWizard83/wows-tools

#WORKAROUND TEMPORARY BUG
sudo apt-get install nano
nano /var/www/wows-tools/storage/app/public/ratings-expected.json
{"data":{}}

cd /var/www/wows-tools
sudo composer install

chown -R www-data.www-data /var/www/wows-tools
chmod -R 755 /var/www/wows-tools
chmod -R 777 /var/www/wows-tools/storage

##Step 4 – Setup Encryption Key
mv .env.example .env

php artisan key:generate

##Step 5 – Create Database for Laravel

https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-ubuntu-16-04

##Workaround bug7.2
https://stackoverflow.com/questions/48001569/phpmyadmin-count-parameter-must-be-an-array-or-an-object-that-implements-co

Edit file /usr/share/phpmyadmin/libraries/sql.lib.php:

sudo nano /usr/share/phpmyadmin/libraries/sql.lib.php
Replace: count($analyzed_sql_results['select_expr'] == 1)

With:  (count($analyzed_sql_results['select_expr']) == 1)

Restart the server

sudo service apache2 restart


##Step 6 – Apache Configuration

Enabling mod_rewrite

sudo a2enmod rewrite

sudo systemctl restart apache2


nano /etc/apache2/sites-enabled/000-default.conf

	<VirtualHost *:80>

			ServerAdmin webmaster@localhost
			DocumentRoot /var/www/wows-tools/public

			<Directory />
					Options FollowSymLinks
					AllowOverride None
			</Directory>
			<Directory /var/www/wows-tools>
					AllowOverride All
			</Directory>

			ErrorLog ${APACHE_LOG_DIR}/error.log
			CustomLog ${APACHE_LOG_DIR}/access.log combined

	</VirtualHost>

sudo service apache2 restart


##Installation of Curl module

apt install libapache2-mod-php php-curl

service apache2 restart