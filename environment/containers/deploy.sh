#!/bin/bash

PWD=$(< /dev/urandom tr -dc 'A-Za-z0-9-().&@?#,/;+' | head -c30)

docker run --name mariadb -d -e MYSQL_ROOT_PASSWORD=$PWD -e MYSQL_DATABASE=system -p 3306:3306 mariadb:latest
docker run --name phpmyadmin -d --link mariadb:db -p 8080:80 -e PMA_HOST=db -e PMA_PORT=3306 phpmyadmin:latest

echo "Database password is: $PWD"

docker run -d --name=valkey --restart=always -p 6379:6379 valkey/valkey:latest valkey-server --save "" --appendonly no
sleep 2
docker exec -it valkey valkey-cli PING