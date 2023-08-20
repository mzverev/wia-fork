#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#
# To edit the 'php-fpm' base Image, visit its repository on Github
#    https://github.com/Laradock/php-fpm
#
# To change its version, see the available Tags on the Docker Hub:
#    https://hub.docker.com/r/laradock/php-fpm/tags/
#
# Note: Base Image name format {image-tag}-{php-version}
#

ARG PHP_FPM_VERSION
FROM laradock/php-fpm:20230701-${PHP_FPM_VERSION}

ARG PHP_FPM_VERSION

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive


# always run apt update when start and after add new source list, then clean up at end.
RUN set -xe; \
    apt-get update -y && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
      apt-utils \
      #
      #--------------------------------------------------------------------------
      # Mandatory Software's Installation
      #--------------------------------------------------------------------------
      #
      # Mandatory Software's such as ("mcrypt", "pdo_mysql", "libssl-dev", ....)
      # are installed on the base image 'laradock/php-fpm' image. If you want
      # to add more Software's or remove existing one, you need to edit the
      # base image (https://github.com/Laradock/php-fpm).
      #
      # next lines are here becase there is no auto build on dockerhub see https://github.com/laradock/laradock/pull/1903#issuecomment-463142846
      libzip-dev zip unzip && \
      if [ ${PHP_FPM_VERSION} = "7.3" ] || [ ${PHP_FPM_VERSION} = "7.4" ] || [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
        docker-php-ext-configure zip; \
      else \
        docker-php-ext-configure zip --with-libzip; \
      fi && \
      # Install the zip extension
      docker-php-ext-install zip && \
      php -m | grep -q 'zip'


###########################################################################
# SSH2:
###########################################################################

RUN apt-get -y install libssh2-1-dev && \
    pecl install -a ssh2-1.3.1 && \
    docker-php-ext-enable ssh2

###########################################################################
# SOAP:
###########################################################################

RUN rm /etc/apt/preferences.d/no-debian-php && \
    apt-get -y install libxml2-dev php-soap && \
    docker-php-ext-install soap

###########################################################################
# XSL:
###########################################################################

RUN apt-get -y install libxslt-dev && \
    docker-php-ext-install xsl

###########################################################################
# Phpdbg:
###########################################################################

# Load the xdebug extension only with phpunit commands
RUN apt-get install -y php-phpdbg


###########################################################################
# PHP REDIS EXTENSION
###########################################################################

#RUN pecl install -o -f redis \
#    && rm -rf /tmp/pear \
#    && docker-php-ext-enable redis


###########################################################################
# Xhprof:
###########################################################################

RUN set -eux; \
      # Install the php xhprof extension
      pecl install xhprof; \
      docker-php-ext-enable xhprof; \
      php -m | grep -q 'xhprof';


###########################################################################
# Mysqli Modifications:
###########################################################################

RUN docker-php-ext-install mysqli


###########################################################################
# Install additional locales:
###########################################################################

RUN apt-get install -y locales \
    && echo '' >> /usr/share/locale/locale.alias \
    && temp="${ADDITIONAL_LOCALES%\"}" \
    && temp="${temp#\"}" \
    && for i in ${temp}; do sed -i "/$i/s/^#//g" /etc/locale.gen; done \
    && locale-gen

###########################################################################
# MySQL Client:
###########################################################################

RUN apt-get -y install default-mysql-client


###########################################################################
# ping:
###########################################################################

RUN apt-get -y install inetutils-ping


###########################################################################
# xDebug:
###########################################################################

ARG INSTALL_XDEBUG=false
ARG XDEBUG_PORT=9003

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
  # Install the xdebug extension
  # https://xdebug.org/docs/compat
  if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ] || { [ $(php -r "echo PHP_MAJOR_VERSION;") = "7" ] && { [ $(php -r "echo PHP_MINOR_VERSION;") = "4" ] || [ $(php -r "echo PHP_MINOR_VERSION;") = "3" ] ;} ;}; then \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
      pecl install xdebug-3.2.1; \
    else \
      pecl install xdebug-3.1.6; \
    fi; \
  else \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "5" ]; then \
      pecl install xdebug-2.5.5; \
    else \
      if [ $(php -r "echo PHP_MINOR_VERSION;") = "0" ]; then \
        pecl install xdebug-2.9.0; \
      else \
        pecl install xdebug-2.9.8; \
      fi \
    fi \
  fi && \
  docker-php-ext-enable xdebug \
;fi

# Copy xdebug configuration for remote debugging
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ] || { [ $(php -r "echo PHP_MAJOR_VERSION;") = "7" ] && { [ $(php -r "echo PHP_MINOR_VERSION;") = "4" ] || [ $(php -r "echo PHP_MINOR_VERSION;") = "3" ] ;} ;}; then \
  sed -i "s/xdebug.remote_host=/xdebug.client_host=/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.remote_connect_back=0/xdebug.discover_client_host=false/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.remote_port=9000/xdebug.client_port=${XDEBUG_PORT}/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.profiler_enable=0/; xdebug.profiler_enable=0/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.profiler_output_dir=/xdebug.output_dir=/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.remote_mode=req/; xdebug.remote_mode=req/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.remote_autostart=0/xdebug.start_with_request=yes/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.remote_enable=0/xdebug.mode=debug/" /usr/local/etc/php/conf.d/xdebug.ini \
;else \
  sed -i "s/xdebug.remote_autostart=0/xdebug.remote_autostart=1/" /usr/local/etc/php/conf.d/xdebug.ini && \
  sed -i "s/xdebug.remote_enable=0/xdebug.remote_enable=1/" /usr/local/etc/php/conf.d/xdebug.ini \
;fi
RUN sed -i "s/xdebug.cli_color=0/xdebug.cli_color=1/" /usr/local/etc/php/conf.d/xdebug.ini


###########################################################################
# Check PHP version:
###########################################################################

RUN set -xe; php -v | head -n 1 | grep -q "PHP ${PHP_FPM_VERSION}."



#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

COPY default.ini /usr/local/etc/php/conf.d
COPY xx-docker.conf /usr/local/etc/php-fpm.d/

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Adding the faketime library to the preload file needs to be done last
# otherwise it will preload it for all commands that follow in this file
RUN echo "/usr/lib/x86_64-linux-gnu/faketime/libfaketime.so.1" > /etc/ld.so.preload


# Install SSL
COPY ssl/caRoot/ /caRoot/

RUN apt-get update && apt install -y libnss3-tools
RUN curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64" && \
    chmod +x mkcert-v*-linux-amd64 && \
    mv mkcert-v*-linux-amd64 /usr/local/bin/mkcert

RUN export CAROOT=/caRoot/ && \
    mkcert -install

# Configure locale.
ARG LOCALE=POSIX
ENV LC_ALL ${LOCALE}

WORKDIR /var/www/html

CMD ["php-fpm"]

EXPOSE 9000 9003
