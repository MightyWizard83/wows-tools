##Ubuntu 16.04
https://tecadmin.net/install-laravel-framework-on-ubuntu/
http://www.laravelinterviewquestions.com/2017/04/upgrading-php-version-on-ubantu-1604.html#sthash.v8XIHlt0.sLDPzSpt.dpbs
https://askubuntu.com/questions/493460/how-to-install-add-apt-repository-using-the-terminal
https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-ubuntu-16-04


sudo apt-get update

sudo apt-get upgrade

Step 0 – HOTFIX special characters

sudo apt-get install software-properties-common
sudo apt-get install python3-software-properties
sudo apt-get install python-software-properties

sudo apt-get install -y language-pack-en-base
sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

Step 1 – Install LAMP

sudo apt-get update

sudo apt-get install -y php7.2 php7.2-mcrypt php7.2-gd php7.2-mbstring php7.2-xml
sudo apt-get install apache2 libapache2-mod-php7.2
sudo apt-get install mysql-server php7.2-mysql

##Install Composer

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

#Install Laravel
(checout from git)

cd /var/www/laravel
sudo composer install

chown -R www-data.www-data /var/www/wows-tools
chmod -R 755 /var/www/wows-tools
chmod -R 777 /var/www/wows-tools/storage

##Step 4 – Setup Encryption Key
mv .env.example .env

php artisan key:generate

##Step 5 – Create Database for Laravel

https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-ubuntu-16-04

##Step 6 – Apache Configuration

Enabling mod_rewrite

sudo a2enmod rewrite

sudo systemctl restart apache2


nano /etc/apache2/sites-enabled/000-default.conf

	<VirtualHost *:80>

			ServerAdmin webmaster@localhost
			DocumentRoot /var/www/laravel/public

			<Directory />
					Options FollowSymLinks
					AllowOverride None
			</Directory>
			<Directory /var/www/laravel>
					AllowOverride All
			</Directory>

			ErrorLog ${APACHE_LOG_DIR}/error.log
			CustomLog ${APACHE_LOG_DIR}/access.log combined

	</VirtualHost>

sudo service apache2 restart


##Installation of Curl module

apt install libapache2-mod-php php-curl

service apache2 restart