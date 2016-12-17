# bootstrapi
This is my JSONAPI skeleton. Built using Slim 3, Eloquent, Zend-ACL

## Inside:
* Slim3 http://www.slimframework.com/
* ORM Eloquent http://laravel.su/docs/5.2/eloquent
* Zend ACL https://zendframework.github.io/zend-permissions-acl/
* JsonApi https://github.com/neomerx/json-api
* JWT https://github.com/firebase/php-jwt
* SwiftMailer http://swiftmailer.org/
* ApiDocJS http://apidocjs.com/

## Feature
* JWT-token authorization
* Validation request
* ACL role based
* Support base CRUD operation
* Filtering && Sorting && Pagination
* DB migration
* CLI-tools
* JSONAPI negotiation
* Generated documentation
* Log

## Demo
[Example documentation](http://docs.bootstrapi.demostage.ru/)

[Example client (Ember.js application)](http://bootstrapi.demostage.ru/)

## Requirements
* PHP >= 5.6
* Composer
* MySQL / PostgreSQL
* NodeJs && NPM && ApiDocJs (for docs generate)

## Installing
1) create new project
```
$ composer create-project -n -s dev pmurkin/bootstrapi my-api
```

2) change config files:
```
$ nano config/db.php
$ nano config/params.php
$ nano app/apidoc.json # require set "url"
$ nano version.sh
```

3) configure nginx

Be sure to define environment variables:
```
APPLICATION_ENV
SECRET_KEY
```

Example configuration:
```
server {
    listen 80 ;
    server_name     hostname;
    error_log       /path/to/nginx/logs/hostname.error.log;
    access_log      /path/to/nginx/logs/hostname.access.log;
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

server {
    listen 80 ;
    server_name     docs.hostname;
    error_log       /path/to/nginx/logs/hostname.error.log;
    access_log      /path/to/nginx/logs/hostname.access.log;
    index           index.html;
    root            /path/to/projects/hostname/docs;

    location / {
        try_files $uri $uri/ /index.html?$args;
    }

    location ~* (.+\.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar|woff|woff2|ttf|eot|svg))$ {
        try_files $uri =404;
    }
}
```

4) migration
```
$ php partisan migrate --seed
```

5) generate documentation (optional)
```
$ php partisan docsgenerate
```