FROM php:7.3.19-apache-stretch


RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    unzip \
    unixodbc \
    unixodbc-dev \
    freetds-dev \
    freetds-bin \
    tdsodbc \
    libxml2-dev \
    libicu-dev \
    locales-all \
    task-brazilian-portuguese \
    ghostscript \
    libaio-dev \
    unixodbc \
    tdsodbc \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install sockets

# Set timezone
RUN rm /etc/localtime &&\
    ln -s /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime &&\
    "date"

# install pecl
RUN curl -O http://pear.php.net/go-pear.phar \
    ; /usr/local/bin/php -d detect_unicode=0 go-pear.phar


# xDEBUG testing
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo 'xdebug.remote_enable=on' >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.remote_host=10.0.75.1' >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/conf.d/xdebug.ini \
    && a2enmod rewrite


# Driver Mysql Pdo
RUN docker-php-ext-install pdo pdo_mysql zip 
RUN docker-php-ext-install mysqli


#Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN export PATH="$PATH:$HOME/.composer/vendor/bin"
RUN echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> ~/.bashrc

#Php ini
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

#Document root change 
ENV APACHE_DOCUMENT_ROOT=/var/www/html/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#Perm RW
RUN a2enmod rewrite \
    && a2enmod headers


# SOAP api extension
RUN docker-php-ext-install soap \
    && docker-php-ext-install pdo intl mbstring \
    && docker-php-ext-configure pdo_dblib --with-libdir=/lib/x86_64-linux-gnu

#XDEBUG
RUN docker-php-ext-install pdo_dblib \
    && docker-php-ext-enable intl mbstring pdo_dblib