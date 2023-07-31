FROM php:8.2.5-fpm
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# install nodejs
RUN curl -s https://deb.nodesource.com/setup_20.x | bash
RUN apt-get install -y nodejs make g++

# install yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get install -y yarn

# allow ports<1024 to nodejs
RUN apt-get install -y libcap2-bin
RUN /sbin/setcap CAP_NET_BIND_SERVICE=+eip /usr/bin/node

ENV TZ=Europe/Warsaw
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y zip git libicu-dev

COPY --from=composer:2.5.5 /usr/bin/composer /usr/local/bin/composer
COPY ./docker/php-fpm/php-ini-overrides.ini $PHP_INI_DIR/php.ini

ENV PHP_FPM_DOCKER_CONF=$PHP_INI_DIR-fpm.d/zz-docker.conf
RUN echo 'pm.max_children = 50' >> $PHP_FPM_DOCKER_CONF \
    && echo 'pm.max_requests = 500' >> $PHP_FPM_DOCKER_CONF  \
    && echo 'pm = static' >> $PHP_FPM_DOCKER_CONF

RUN docker-php-ext-install pdo_mysql pcntl intl opcache\
    && docker-php-ext-enable pcntl intl opcache
