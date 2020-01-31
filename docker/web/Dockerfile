FROM banderson/drupal

LABEL maintainer="Brendan Anderson <brendan_anderson@hcpss.org>"
LABEL vendor="Howard County Public School System"
LABEL org.hcpss.api.version="2.0.0"

# Create the Drupal structure
COPY drupal/web/autoload.php               /var/www/drupal/web/autoload.php
COPY drupal/web/modules/custom             /var/www/drupal/web/modules/custom
COPY drupal/scripts                        /var/www/drupal/scripts
COPY drupal/drush                          /var/www/drupal/drush
COPY drupal/composer.json                  /var/www/drupal/composer.json
COPY drupal/composer.lock                  /var/www/drupal/composer.lock
COPY drupal/config                         /var/www/drupal/config
#COPY drupal/web/sites/default/settings.php /var/www/drupal/web/sites/default/settings.php
COPY drupal/web/robots.txt                 /var/www/drupal/web/robots.txt
COPY drupal/load.environment.php           /var/www/drupal/load.environment.php

RUN composer install -d /var/www/drupal

COPY docker/web/entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
