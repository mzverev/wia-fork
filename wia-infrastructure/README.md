# Initiative Development Environment

Multi-purpose containers to set up the infrastructure for your development environment. The services are mostly based on [laradock](https://github.com/laradock/laradock) environment.

## Requirements
- Docker CE 20.0+
- docker-compose 1.27+ 

## Install docker
To install docker refer to <https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu>.

#### Use docker command without sudo
```
$ sudo gpasswd -a <your_user> docker
$ sudo service docker restart
```
A logout and login will be required.
You should be able to execute the docker commands withoud sudo.

#### Install docker-compose
To install docker-compose refer to https://docs.docker.com/compose/install/


## Containers (Services)
Please refer to the `docker-compose.yml` file to see the list of all services. you can also run the following command to see the list of services:
```
docker-compose ps
```


## Usage
1. Make sure that `Docker` and `docker-compose` are installed.
2. Change the parameters in the `.env` file if needed.
3. Copy `.env_dev-example` to `.env-dev` and set the credentials.

#### Build containers

You are encouraged to look at the `.env` file to see the existing parameters and change them if needed.
```
$ ./build_dev_environment
```

#### Access the workspace container
```
$ docker exec -it devenv_workspace bash
```
#### See the apache logs of php-fpm
```
$ docker logs -n 100 -f devenv_php-fpm
```

#### See the php-fpm logs
It is recommended to create an alias in your `.bashrc` or `.zshrc` to see the php logs quickly:
```
fpmlogs='docker logs -n 100 -f devenv_php-fpm'
```
Then `source` the `.bashrc` or `.zshrc` (or restart the terminal) and run `fpmlogs`.

#### Use dependency management tools for Initiative
All necessary tools are installed in `workspace` container. To use dependency management tools for Initiative you need to use a non-root user in the container. A user named `initiative` is created in the `workspace` container.

To change the user, connect into the container and run the following command:
```
$ su initiative
```

Then to build the dependencies run::
```
$ cd /var/www/html/initiative/layouts/
$ npm ci && grunt
```
These commands will build Initiative for dev environment. For the production environment (or any other environment that doesn't need devDependencies) you can use:
```
$ npm ci --only=production && grunt
```

## Environment variables in the workspace container
To make environment variables available in the workspace container, you need to add them in the `.env-dev` file.
There is already the versioned file `.env-dev-example` in the root.

## Avoid SSL warning in browser
You need to install the CA for your system. All certificates are created using the CA root (All developers use the same
CA root).
Do as follows:
### Step 1: Install mkcert
Please follow the installation guides for Linux or macOS from [https://github.com/FiloSottile/mkcert](https://github.com/FiloSottile/mkcert)

### Step 2: Install the CA in your machine:
Run these two commands (assuming that you are in the root of abos_dev_environment):
```
export CAROOT=$PWD/apache2/ssl/caRoot/
mkcert -install
```
You should see the following output:
```
The local CA is now installed in the system trust store! ⚡️
The local CA is now installed in the Firefox and/or Chrome/Chromium trust store (requires browser restart)! 🦊
```

## Access the applications
All PHP applications are served by apache2. The default IP of apache2 is **172.70.0.101**. You can easily access to an
application using `http://172.70.0.101/<application-dir>`.
A better way is to use realistic domain names with http2 and a valid SSL. To do so, you have two options:

**Option 1 (recommended)-** No configuration is needed. Just access the applications via the already configured domains:
```
clientspace.172.70.0.101.nip.io
imanagerapi.172.70.0.101.nip.io
initiative.172.70.0.101.nip.io
instance-lookup.172.70.0.101.nip.io
instancemanager.172.70.0.101.nip.io
media.172.70.0.101.nip.io
trial-api.172.70.0.101.nip.io
trial.172.70.0.101.nip.io
```
_Note:_ Make sure you have installed the CA (see above) to avoid browser warnings.

**Option 2-** Set the following lines in your `/etc/hosts` file:
```
172.70.0.101   clientspace.com
172.70.0.101   imanagerapi.com
172.70.0.101   initiative.com
172.70.0.101   instance-lookup.com
172.70.0.101   instancemanager.com
172.70.0.101   media.com
172.70.0.101   trial-api.com
172.70.0.101   trial.com
```
Then the applications are accessible via the defined domains.

_Note:_ Make sure you have installed the CA (see above) to avoid browser warnings.

## Some useful commands

List the running containers:
```
$ docker ps
```

Remove all containers manually:
```
$ docker rm -f $(docker ps -qa)
```

Remove all unused networks:
```
$ docker network prune
```

Rebuild an image without cache (e.g. php-fpm), then run the containers:
```
docker-compose build php-fpm --no-cache
./build_dev_environment
```

Find the zend server admin password:
```
$ docker logs devenv_zendphp70 | grep password
```

Connect to a container tty:
```
$ docker exec -it <container-name> bash
```

To exit from the container tty:
```
Hold `Ctrl+d`, or type `exit`.
```