#!/usr/bin/env bash

chown -R www-data:www-data /var/www/drupal/web/sites/default/files
chown -R www-data:www-data /var/www/drupal/config
chown root:root /var/www/drupal/web/sites/default/settings.php
chmod 444 /var/www/drupal/web/sites/default/settings.php

# Wait for MySQL
while ! mysqladmin ping -h"$MYSQL_HOSTNAME" --silent; do
    echo "Waiting for database connection..."
    sleep 5
done

drush --root=/var/www/drupal/web cc drush
drush --root=/var/www/drupal/web csim -y
drush --root=/var/www/drupal/web cr
drush --root=/var/www/drupal/web updb -y

exec "$@"
