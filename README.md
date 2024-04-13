## Copy .env
```
cp .env.example .env
```

## Docker build
```
docker-compose build
docker-compose up
```

## Access to container
```
docker-compose run app bash
```

## APP install composer and generate key
```
composer install
php artisan key:generate
```

## DB
```
php artisan migrate
php artisan db:seed
php artisan passport:install
php artisan optimize
```

## npm
```
npm install

Dev environment
npm run watch

Product environment
npm run prod
```

## yarn
```
yarn install

Dev environment
yarn run watch

Product environment
yarn run prod
```


## Socket setup
```
docker-compose run socket bash
npm install
```

## Add xdebug into launch.json (VSCode)
```
{
    "name": "XDebug on docker",
    "type": "php",
    "request": "launch", 
    "port": 9004,
    "stopOnEntry": true,
    "pathMappings": {
        "/var/www/html": "${workspaceRoot}/"
    }
},
```

## Web URL
```
http://127.0.0.1:8085
```