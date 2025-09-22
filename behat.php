<?php

use Behat\Config\Config;
use Behat\Config\Profile;
use Behat\Config\Suite;
use Test\Acceptance\FeatureContext;

return new Config()
    ->withProfile(new Profile('default')
        ->withSuite(new Suite('acceptance')
            ->withContexts(FeatureContext::class)
            ->withPaths('%paths.base%/test/Acceptance/features')));
