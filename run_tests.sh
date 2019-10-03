#!/usr/bin/env bash

vendor/bin/phpstan analyse
vendor/bin/phpunit --testsuite unit -v
vendor/bin/behat --suite acceptance -v
