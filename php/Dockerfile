FROM phpswoole/swoole:4.8-php8.0
RUN apt update && apt install -y lua5.1 liblua5.1-0 liblua5.1-dev
RUN pecl install LuaSandbox
RUN docker-php-ext-enable luasandbox 
RUN cp /usr/local/lib/php/build/run-tests.php /usr/local/bin/run-tests.php
RUN chmod 755 /usr/local/bin/run-tests.php
