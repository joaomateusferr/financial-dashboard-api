#!/bin/bash

CLEAN_IMAGES=$1

ACTIVE_CONTAINERS=$(docker ps -a -q)

if [ -z "$ACTIVE_CONTAINERS" ];then
    echo "No active container!"
else
    docker stop $ACTIVE_CONTAINERS
    docker rm $ACTIVE_CONTAINERS
fi

ACTIVE_NETWORKS=$(docker network ls --format '{{.Name}}' | grep -vE '^bridge$|^host$|^none$')

if [ -z "$ACTIVE_NETWORKS" ];then
    echo "No active network!"
else
    docker network rm $ACTIVE_NETWORKS
fi

if [ ! -z "$CLEAN_IMAGES" ];then

    ACTIVE_IMAGES=$(docker images -q)

    if [ -z "$ACTIVE_IMAGES" ];then
        echo "No active image!"
    else
        docker rmi $ACTIVE_IMAGES
    fi

fi
