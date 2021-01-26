## stiahni si php 8.0 (xampp)
https://www.apachefriends.org/download.html

## stiahni si composer
https://getcomposer.org/download/

## stiahni si node.js 12.6.x
google + yarn + angular

## run db
* otvor xamp a spusti mysql (port 3306)
* vytvor si prazdnu databazu s nazvom `sp`


## run be
```
cd app/sp-api
composer install # toto je na doinstalovanie chybajucich kniznic 
php -S localhost:8080 -t public

php artisan migrate # toto je na spustenie migracie
```

## run fe
```
cd app/sp-web
yarn install 
yarn start  
```

## vytvorenie migracie
```
cd app/sp-api
php artisan make:migration [nazov migracie]
``` 

## linky
https://auth0.com/blog/developing-restful-apis-with-lumen/
https://laravel.com/docs/8.x/migrations#column-modifiers

https://github.com/voltagead/lumen-angular-demo/blob/master/app/Http/routes.php
https://github.com/voltagead/lumen-angular-demo/tree/master/resources/views
