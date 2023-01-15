#!/bin/bash

GIT_REPOSITORY='https://github.com/joaomateusferr/financial-dashboard.git'

APACHE_SITE_PATH='/var/www/html/'
DEFAULT_APACHE_SITES_PATH='/etc/apache2/sites-available/000-default.conf'

REPO_FOLDER_NAME=$(basename -s .git $GIT_REPOSITORY)
REPO_FOLDER="$APACHE_SITE_PATH$REPO_FOLDER_NAME"

git -C $APACHE_SITE_PATH clone $GIT_REPOSITORY
chmod -R 777 $APACHE_SITE_PATH

sed -i "s|/var/www/html|$REPO_FOLDER/public|g" $DEFAULT_APACHE_SITES_PATH