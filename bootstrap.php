<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

function checkRequirements() {
    if (PHP_VERSION_ID < 70100) {
        throw new \RuntimeException('You need PHP 7.1 to run this application');
    }
}

checkRequirements();
