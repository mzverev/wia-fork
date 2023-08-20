FROM webdevops/apache:ubuntu-18.04

ARG DOCUMENT_ROOT=/var/www/html
ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000
ARG PHP_UPSTREAM_TIMEOUT=60

ARG UID=1000
ARG GID=1000

ENV WEB_PHP_SOCKET=${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}
ENV WEB_DOCUMENT_ROOT=${DOCUMENT_ROOT}
ENV WEB_PHP_TIMEOUT=${PHP_UPSTREAM_TIMEOUT}

ENV LOG_STDOUT=/var/log/apache2/access.log
ENV LOG_STDERR=/var/log/apache2/error.log

RUN apt-get update && apt install -y libnss3-tools
RUN curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64" && \
    chmod +x mkcert-v*-linux-amd64 && \
    mv mkcert-v*-linux-amd64 /usr/local/bin/mkcert

RUN usermod -u $UID www-data && groupmod -g $GID www-data


EXPOSE 80 443

WORKDIR /var/www/html

COPY sites/* /etc/apache2/sites-enabled/

ADD ./startup.sh /opt/startup.sh
RUN sed -i -e 's/\r$//' /opt/startup.sh

ENTRYPOINT ["/opt/docker/bin/entrypoint.sh"]

CMD ["/bin/bash", "/opt/startup.sh"]

EXPOSE 80 443