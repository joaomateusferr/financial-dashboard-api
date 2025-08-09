#!/bin/bash

ACTIVE_CONTAINERS=$(docker ps -a -q)

if [ -z "$ACTIVE_CONTAINERS" ];then
    echo "No active container!"
else
    docker stop $ACTIVE_CONTAINERS
    docker rm $ACTIVE_CONTAINERS
fi
