#!/bin/bash

# docker build --no-cache -t app-image ./environment/image/

NODE_ID=$1
DEVELOPMENT=$2

if [ -z "$NODE_ID" ];then
    echo "No node id!"
else

    docker network create app-network-node-$NODE_ID
    docker run -d -e NODE_ID=$NODE_ID --name=valkey-node-$NODE_ID --restart=always --network app-network-node-$NODE_ID --network-alias valkey  -p 6379:6379 valkey/valkey:latest valkey-server --save "" --appendonly no
    sleep 2
    docker exec -it valkey-node-$NODE_ID valkey-cli PING

    if [ -z "$DEVELOPMENT" ];then
        docker run -t -d -e NODE_ID=$NODE_ID --network app-network-node-$NODE_ID -p 3000:80 --name app-node-$NODE_ID app-image
    else
        TERMINAL_USER=$(whoami)
        docker run -t -d -e NODE_ID=$NODE_ID --network app-network-node-$NODE_ID -p 3000:80 -v /home/$TERMINAL_USER/Sites:/var/www/html --name app-node-$NODE_ID app-image
    fi

fi