#!/bin/bash

ACTIVE_NODES=$(docker ps -a --format "{{.Names}}" | grep -- '-node-')

if [ -z "$ACTIVE_NODES" ];then
    echo "No active node!"
else
    docker start $ACTIVE_NODES
fi