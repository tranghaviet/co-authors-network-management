# Co-authors network management

## Installation

1. Requirements
- PHP >= 5.6.4 (kiểm tra bằng câu lệnh `php --version`)
- MySQL (có thể cài cả PHP và MySQL bằng Xampp/LAMP)
- Composer (chạy được câu lệnh `composer`)
- Git

TIPS: Cài đặt hirak/prestissimo để tiến hành setup project nhanh hơn:
```
composer global require hirak/prestissimo
```

2. Setup
Clone project về bất kì đâu
```
git clone https://github.com/tranghaviet/co-authors-network-management.git;
cd co-authors-network-management
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
