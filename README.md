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

- Clone this repository and `cd` into it.
- Run `bin/install`, which will pull the relevant Docker images and run `composer install`

### Usage

- Run `bin/composer` to use Composer (e.g. `bin/composer require --dev [...]`).
- Run `bin/run_tests` to run the tests.
