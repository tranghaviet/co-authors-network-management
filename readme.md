# Co-authors network management

[![co-authors network management](https://img.shields.io/badge/Status-Awesome-brightgreen.svg)](https://github.com/tranghaviet/co-authors-network-management)
[![StyleCI](https://styleci.io/repos/106026282/shield?branch=master)](https://styleci.io/repos/106026282)
[![Build Status](https://www.travis-ci.org/tranghaviet/co-authors-network-management.svg?branch=master)](https://www.travis-ci.org/tranghaviet/co-authors-network-management)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/build-status/master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d0ac126c068248e29a0e25edd684b5f4)](https://www.codacy.com/app/tranghaviet/co-authors-network-management?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=tranghaviet/co-authors-network-management&amp;utm_campaign=Badge_Grade)
[![HitCount](http://hits.dwyl.io/tranghaviet/co-authors-network-management.svg)](http://hits.dwyl.io/tranghaviet/co-authors-network-management)

## Installation

### Requirements
- PHP >= 7.0.0 (kiểm tra bằng câu lệnh `php --version`)
Reference: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04

Nếu không dùng Xampp mà cài riêng lẻ:
```
sudo apt-get install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.1
# Check
php --version
# packages
sudo apt-get install php7.1-cli php7.1-common php7.1-json php7.1-mysql php7.1-mbstring php7.1-fpm php7.1-opcache libapache2-mod-php php7.1-curl
```
- MariaDB (có thể cài cả PHP và MariaDB bằng Xampp/LAMP)
Nếu không dùng Xampp mà cài riêng lẻ:
```
sudo apt-get install software-properties-common
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
sudo add-apt-repository 'deb [arch=amd64,i386,ppc64el] http://download.nus.edu.sg/mirror/mariadb/repo/10.2/ubuntu xenial main'
sudo apt-get update
sudo apt-get install mariadb-server
# check
mysql -V
sudo systemctl start mysql
# Login with root account (default if you did not setup password when install)
mysql -u root -p
```
- Composer
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
# Check
composer -v
```
On windows, to use php, mysql, composer command you need to add follơwing path to System enviroment:
```
C:\xampp\php
C:\ProgramData\ComposerSetup\bin
C:\xampp\mysql\bin
```
- Git
TIPS: Cài đặt hirak/prestissimo để tiến hành setup project nhanh hơn:
```
composer global require hirak/prestissimo
```
### Setup
Clone project về bất kì đâu
```
git clone https://github.com/tranghaviet/co-authors-network-management.git;
cd co-authors-network-management;
```
Chuyển sanh nhánh dev
```
git checkout dev
```
Cài đặt các Packages:
```
composer install
hoặc
composer update
```
Copy file .env.example thành .env
sửa config trong file .env 
```
DB_DATABASE=co_authors_network_management
DB_USERNAME=root
DB_PASSWORD=
```
Tạo key cho app
```
php artisan key:generate
```
Import Databse vào MySQL và khởi động Database.
Khởi động app:
```
php artisan serve
```
Login vào admin bằng email **admin@example.com** và password là **password**.
### Config file `php.ini`
```
memory_limit=-1
```
### Use co-author sync function
```
php artisan co-author:sync
```
### Enable/disable foreign key check in database
```
php artisan db:foreign-key
```
