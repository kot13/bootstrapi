# slim3-eloquent-jsonapi-skeleton
This is my JSONAPI skeleton. Built using Slim 3, Eloquent, Zend-ACL

## Что внутри
* Slim3 http://www.slimframework.com/
* ORM Eloquent http://laravel.su/docs/5.2/eloquent
* Zend ACL https://zendframework.github.io/zend-permissions-acl/
* JsonApi https://github.com/neomerx/json-api
* JWT https://github.com/firebase/php-jwt
* SwiftMailer http://swiftmailer.org/

## Установка
1) склонировать репозиторий

2) установить зависимости бекенда
```
$ composer install
```

3) исправить конфиг подключения к БД:
```
$ mv config/db.php.sample db.php
$ nano db.php
```

4) исправить конфиг
```
$ mv config/params.php.sample params.php
$ nano params.php
```

5) создать необходимые папки
```
$ mkdir log
$ chmod -R 0777 log
```

6) настроить nginx

Обязательно нужно определить переменные окружения:
```
APPLICATION_ENV
SECRET_KEY
```

Пример конфигурации:
```
server {
    listen 80 ;
    server_name     hostname;
    error_log       /path/to/nginx/logs/hostname.error.log;
    access_log      /path/to/nginx/logs/hostname.access.log main;
    index           /frontend/index.html index.html;

    root   /path/to/projects/hostname;

    location ~* (.+\.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar|woff|woff2|ttf|eot|svg))$ {
        root   /path/to/projects/hostname/frontend;
        try_files       $uri =404;
    }

    location ~ /api/ {
        if (!-e $request_filename) {rewrite ^/(.*)$ /public/index.php?q=$1 last;}
    }

    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
        fastcgi_param APPLICATION_ENV develop;
        fastcgi_param SECRET_KEY mysecretkey;
        fastcgi_pass   127.0.0.1:9000;
    }

    location / {
        if (!-e $request_filename) {rewrite ^/(.*)$ /frontend/index.html?q=$1 last;}
    }
}
```

7) выполнить миграции
```
$ php partisan migrate up
```