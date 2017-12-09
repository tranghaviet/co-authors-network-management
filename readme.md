# Co-authors network management

[![co-authors network management](https://img.shields.io/badge/Status-Awesome-brightgreen.svg)](https://github.com/tranghaviet/co-authors-network-management)
[![StyleCI](https://styleci.io/repos/106026282/shield?branch=master)](https://styleci.io/repos/106026282)
[![Build Status](https://www.travis-ci.org/tranghaviet/co-authors-network-management.svg?branch=master)](https://www.travis-ci.org/tranghaviet/co-authors-network-management)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tranghaviet/co-authors-network-management/build-status/master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d0ac126c068248e29a0e25edd684b5f4)](https://www.codacy.com/app/tranghaviet/co-authors-network-management?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=tranghaviet/co-authors-network-management&amp;utm_campaign=Badge_Grade)
[![HitCount](http://hits.dwyl.io/tranghaviet/co-authors-network-management.svg)](http://hits.dwyl.io/tranghaviet/co-authors-network-management)

## Hướng dẫn cài đặt
Hướng dẫn này dành cho máy tính chạy hệ điều hành Ubuntu (hoặc các nền tảng Linux khác tương tự Ubuntu).
Nếu bạn muốn cài đặt trên windows thì có thể tham khảo các hướng dẫn trên mạng để đáp
ứng các yêu cầu sau.

Bạn có thể làm theo hướng dẫn cài đặt tại: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04
### Yêu cầu
- PHP phiên bản lớn hơn 7.1.0 (kiểm tra bằng câu lệnh `php --version`)
- MariaDB phiên bản lớn hơn 10.1.0 (kiểm tra bằng câu lệnh `mysql --version`)
- Composer phiên bản lớn hơn 1.3.0 (kiểm tra bằng câu lệnh `composer --version`)

Bạn có thể dùng XAMPP/LAMP để cài cả PHP, MariaDB, Apache. Nếu không dùng XAMPP/LAMP mà cài
riêng lẻ thì có thể chạy các câu lệnh sau:
 
Cài PHP:
 
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
Cài MariaDB:
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
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
# Check
composer -v
```
Trên Windows, để sử dụng lệnh php, mysql, composer, bạn cần phải thêm
đường dẫn sau vào môi trường hệ thống:
```
C:\xampp\php # cho máy dùng xampp
C:\ProgramData\ComposerSetup\bin
C:\xampp\mysql\bin # cho máy dùng xampp
```
Ghi chú: Cài đặt `hirak/prestissimo` để tiến hành cài đặt project nhanh hơn:
```
composer global require hirak/prestissimo
```
### Cài đặt
Giải nén file mã nguồn.

Chuyển đến thư mục `co-authors-network-management`
```
cd co-authors-network-management;
```
Cài đặt các thư viện:
```
composer install
# hoặc chạy
composer update
```
Copy file `.env.example` và lưu thành `.env`
```
cp .env.example .env
```
Sửa config trong file `.env`. Trong đó `DB_USERNAME` là tên tài khoản trong MariaDB
đã cài đặt mà có quyền tạo bảng, đọc cơ sở dữ liệu và `DB_PASSWORD` là mật khẩu
của tài khoản đó (khuyến cáo dùng tài khoản `root` mặt định khi cài MariaDB). Ví dụ:
```
DB_DATABASE=co_authors_network
DB_USERNAME=root
DB_PASSWORD=
```
Tạo key cho app:
```
php artisan key:generate
```
Đăng nhập vào MariaDB với tài khoản `DB_USERNAME` và `DB_PASSWORD` ở trên,
giả sử là tài khoản `root`

```
mysql -u root -p
```
Tạo một Cơ sở dữ liệu tên `co_authors_network` với charecter set là `utf8`
và collation `utf8_unicode_ci`
```
create database co_authors_network character set UTF8 collate utf8_unicode_ci
```
Tạo các bảng trong CSDL
```
php artisan migrate --seed
```
### Cấu hình file `php.ini`
```
memory_limit=-1
post_max_size=0
```
Khởi động chương trình:
```
php artisan serve
```
Truy cập vào địa chỉ http://127.0.0.1:8000 để sử dụng.

Login vào admin bằng email **admin@example.com** và password là **password**
tại địa chỉ `/login`
