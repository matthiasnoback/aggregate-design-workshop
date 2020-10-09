#!/usr/bin/env sh

vendor/bin/phpstan analyse
vendor/bin/phpunit --testsuite unit
#vendor/bin/behat --suite acceptance --tags "~@ignore"
