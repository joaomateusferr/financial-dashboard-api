# Financial Dashboard API

A simple API that aims to create a dashboard to help automate finances and investments.

## Preparing the development environment

### Installing PHP dependencies in ubuntu

```shell
sudo apt-get update && sudo apt-get install -y php composer php-curl php-mysql php-mbstring php-zip php-dom php-gd php-redis
```

### Install composer dependencies

```shell
composer install
```
### Run all necessary containers and data structures

```shell
composer run-script --timeout=120 prepare-environment-dev
```

### Start the development server

```shell
php -S localhost:8888 -t ./public/
```

### Clear all application containers

```shell
composer run-script --timeout=60 environment-clear
```