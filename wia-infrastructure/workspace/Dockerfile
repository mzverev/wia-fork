ARG WORKSPACE_PHP_VERSION

FROM laradock/workspace:20230701-${WORKSPACE_PHP_VERSION}
ARG WORKSPACE_PHP_VERSION

# Start as root
USER root

#####################################
# Install required tools
#####################################

RUN	 add-apt-repository ppa:git-core/ppa -y && set -xe; \
     apt-get update -y && \
     pecl channel-update pecl.php.net && \
     apt-get install -yqq \
      supervisor \
	  mysql-client git sudo wget ftp \
      iputils-ping && \
	  php -m | grep -q 'zip'

#####################################
# Create Non-Root User:
#####################################

ARG PUID=1000
ARG PGID=1000

ENV PUID ${PUID}
ENV PGID ${PGID}

ARG NON_ROOT_USER=wia
ENV NON_ROOT_USER ${NON_ROOT_USER}

RUN \
	groupadd -g ${PGID} ${NON_ROOT_USER} && \
    useradd -u ${PUID} -g ${NON_ROOT_USER} -m ${NON_ROOT_USER} && \
	usermod -p "*" ${NON_ROOT_USER} -s /bin/bash && \
    usermod -aG sudo ${NON_ROOT_USER} && \
    echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

###########################################################################
# Set Timezone
###########################################################################

ARG TZ=UTC
ENV TZ ${TZ}

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


###########################################################################
# Node / NVM:
###########################################################################

USER ${NON_ROOT_USER}

# Check if NVM needs to be installed
ARG NODE_VERSION=node
ENV NODE_VERSION ${NODE_VERSION}

RUN echo NODE_VERSION

ENV NVM_DIR /home/${NON_ROOT_USER}/.nvm

RUN mkdir -p $NVM_DIR && \
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash \
        && . $NVM_DIR/nvm.sh \
        && nvm install ${NODE_VERSION} \
        && nvm use ${NODE_VERSION} \
        && nvm alias ${NODE_VERSION} \
        && npm install -g @vue/cli \
        && ln -s `npm bin --global` /home/${NON_ROOT_USER}/.node-bin

# Add PATH for node
ENV PATH $PATH:/home/${NON_ROOT_USER}/.node-bin
RUN npm config set loglevel verbose

# Wouldn't execute when added to the RUN statement in the above block
# Source NVM when loading bash since ~/.profile isn't loaded on non-login shell
RUN echo "" >> ~/.bashrc && \
    echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm' >> ~/.bashrc

# Add NVM binaries to root's .bashrc
USER root

RUN echo "" >> ~/.bashrc && \
    echo 'export NVM_DIR="/home/${NON_ROOT_USER}/.nvm"' >> ~/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm' >> ~/.bashrc


###########################################################################
# Check PHP version:
###########################################################################

RUN set -xe; php -v | head -n 1 | grep -q "PHP ${WORKSPACE_PHP_VERSION}"


#################################################
# Redis-cli
#################################################
#USER root
#RUN cd /tmp && wget http://download.redis.io/redis-stable.tar.gz && \
#    tar xvzf redis-stable.tar.gz && \
#    cd redis-stable && \
#    make && \
#    cp src/redis-cli /usr/local/bin/ && \
#    chmod 755 /usr/local/bin/redis-cli


################################################
# Install SSL
################################################

COPY ssl/caRoot/ /caRoot/

RUN apt-get update && apt install -y libnss3-tools
RUN curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64" && \
    chmod +x mkcert-v*-linux-amd64 && \
    mv mkcert-v*-linux-amd64 /usr/local/bin/mkcert

RUN export CAROOT=/caRoot/ && \
    mkcert -install


################################################
# Configure supervisor to run laravel workers
################################################

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Set default work directory
WORKDIR /var/www/html