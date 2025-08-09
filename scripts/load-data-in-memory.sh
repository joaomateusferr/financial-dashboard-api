#!/bin/bash

SCRIPT_FOLDER_PATH=$(dirname $0)
DATA_FOLDER_PATH=$1

SERVERS_LIST_FILE_NAME="servers-list.json"
DB_CREDENTIALS_FILE_NAME="db-credentials.json"

if [ -z "$DATA_FOLDER_PATH" ];then

    echo "Do not leave the data folder path empty!"
    exit 1

fi

echo "Selected path - $DATA_FOLDER_PATH"

if [ ! -d "$DATA_FOLDER_PATH" ]; then

    echo "$DATA_FOLDER_PATH does not exist!"
    exit 2

fi

echo "$DATA_FOLDER_PATH exist."

if [ ! -e "$DATA_FOLDER_PATH/$SERVERS_LIST_FILE_NAME" ]; then

    echo "$DATA_FOLDER_PATH/$SERVERS_LIST_FILE_NAME  does not exist!"
    exit 3

fi

echo "$DATA_FOLDER_PATH/$SERVERS_LIST_FILE_NAME  exist."

if [ ! -e "$DATA_FOLDER_PATH/$DB_CREDENTIALS_FILE_NAME" ]; then

    echo "$DATA_FOLDER_PATH/$DB_CREDENTIALS_FILE_NAME  does not exist!"
    exit 4

fi

echo "$DATA_FOLDER_PATH/$DB_CREDENTIALS_FILE_NAME exist."

echo "Running scripts ..."

php $SCRIPT_FOLDER_PATH/loaders/servers-list.php $DATA_FOLDER_PATH/$SERVERS_LIST_FILE_NAME

if [ $? -ne 0 ] ; then

    echo "servers-list.php - $?"
    exit 5

fi

php $SCRIPT_FOLDER_PATH/loaders/db-credentials.php $DATA_FOLDER_PATH/$DB_CREDENTIALS_FILE_NAME

if [ $? -ne 0 ] ; then

    echo "db-credentials.php - $?"
    exit 6

fi