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
- PHP >= 5.6.4 (kiểm tra bằng câu lệnh `php --version`)
- MySQL (có thể cài cả PHP và MySQL bằng Xampp/LAMP)
- Composer (chạy được câu lệnh `composer`)
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
Import Databse vào MySQL và khởi động Databse.
Khởi động app:
```
php artisan serve
```
Login vào admin bằng email **admin@example.com** và password là **password**.
