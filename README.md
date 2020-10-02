# Aggregate design workshop

## Option 1: Use with locally installed PHP

### Requirements

- PHP (>= 7.4)
- Composer

### Getting started

- Clone this repository and `cd` into it.
- Run `composer install --prefer-dist` to install the project's dependencies.

### Usage

- Run `./run_tests.sh` to run the tests.

## Option 2: Use with Docker

### Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Getting started

- Set up environment variables `HOST_UID` and `HOST_GID` with their correct values:

  ```
  export HOST_GID=$(id -g)
  export HOST_UID=$(id -u)
  ```

- Clone this repository and `cd` into it.
- Run `docker-compose pull`
- Run `docker/composer.sh install --prefer-dist` to install the project's dependencies.

### Usage

- Run `docker/composer.sh` to use Composer (e.g. `docker/composer.sh require --dev [...]`).
- Run `docker/run_tests.sh` to run the tests.
