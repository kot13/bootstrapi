# slim3-eloquent-jsonapi-skeleton
This is my JSONAPI skeleton. Built using Slim 3, Eloquent, Zend-ACL

## Установка
1) склонировать репозиторий

2) установить зависимости бекенда
```
composer install
```

3) исправить конфиг подключения к БД:
```
mv config/db.php.sample db.php
nano db.php
```

4) исправить конфиг
```
mv config/params.php.sample params.php
nano params.php
```

5) настроить nginx
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

6) выполнить миграции
```
php partisan migrate up