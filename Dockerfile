FROM php:7.4

ARG DEFAULT_APP_DIR=/var/www/http-smoke-test-crawler
ARG BUILD_ENV=production

USER root

RUN apt-get update && apt-get install -y vim nano less

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

COPY . $DEFAULT_APP_DIR

RUN chgrp -R www-data $DEFAULT_APP_DIR
RUN chmod -R 775 $DEFAULT_APP_DIR/storage
RUN chmod -R 775 $DEFAULT_APP_DIR/bootstrap
RUN chmod -R 775 $DEFAULT_APP_DIR/public
RUN chmod 775 $DEFAULT_APP_DIR/composer.json

RUN if [ "$BUILD_ENV" = "local" ] ; \
then \
    pecl install xdebug-3.0.4 && docker-php-ext-enable xdebug ; \
    printf 'xdebug.mode=debug\n\
    xdebug.start_with_request=trigger\n\
    xdebug.client_port=9003\n\
    xdebug.discover_client_host=true\n\
    xdebug.max_nesting_level=512' \
    >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ; \
fi

#USER 33

WORKDIR $DEFAULT_APP_DIR
STOPSIGNAL SIGTERM

CMD ["php"]
