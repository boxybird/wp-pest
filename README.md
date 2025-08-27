# WP Pest — Example WordPress plugin using Pest PHP

This plugin demonstrates how to integrate [Pest PHP](https://pestphp.com/) with a WordPress plugin for Unit, Feature, and simple Browser tests.

It focuses on four key pieces of configuration for WordPress inside this plugin directory, as the default Pest installation is intended for Laravel:
- composer.json (dev dependencies, autoloading)
- phpunit.xml (test discovery and bootstrap)
- tests/bootstrap.php (bootstrapping Composer and WordPress for tests)
- tests/TestCest.php

## Running tests
- Run all tests (Pest auto-detects phpunit.xml):
  - vendor\bin\pest

## How this works

### 1) composer.json
Located at composer.json

```
{
  "autoload-dev": {
    "psr-4": {
      "Tests\\": "tests/"
    }
  },
  "require-dev": {
    "pestphp/pest": "^4.0",
    "pestphp/pest-plugin-browser": "^4.0"
  },
  "config": {
    "allow-plugins": {
      "pestphp/pest-plugin": true
    }
  }
}
```

Key points:
- require-dev:
  - pestphp/pest: Pest testing framework.
  - pestphp/pest-plugin-browser: Enables browser-style testing syntax used by tests\Browser\ExampleTest.php.
- autoload-dev (PSR-4): Maps the Tests\ namespace to tests/. Handy if you add helper classes like Tests\TestCase.
- allow-plugins: Allows Pest to register its Composer plugin.

Tip: After changing autoload rules, run composer dump-autoload.


### 2) phpunit.xml
Located at phpunit.xml

```
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.3/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Test Suite">
            <directory suffix="Test.php">./tests</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory suffix=".php">./src</directory>
        </include>
    </source>
</phpunit>
```

Key points:
- bootstrap: tests/bootstrap.php is executed before tests; it loads Composer and WordPress.
- testsuites: Discovers any files in tests that end with Test.php. All example tests follow that convention.

Note: Pest runs on top of PHPUnit, so phpunit.xml is still honored by Pest.


### 3) tests/bootstrap.php
Located at tests\bootstrap.php

```
<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../../../../wp-load.php';
```

What it does:
- Loads Composer’s autoloader from the plugin’s vendor directory so Pest and any test helpers are available.
- Loads WordPress by including wp-load.php from the repository root. This means tests run against your live WordPress site in this environment (not the separate WP core testing suite). Because of that:
  - Ensure your site can boot from wp-load.php without interactive prompts.
  - For the Browser test, ensure get_home_url() resolves correctly and the login credentials used in the test are valid on your site.

If you move the plugin or the repository structure, update the relative path to wp-load.php accordingly.


## Test files overview
- tests\Pest.php — Global Pest configuration and expectations; also binds Feature tests to Tests\TestCase if you create one.
- tests\Unit\ExampleTest.php — Simple unit example using expect().
- tests\Feature\ExampleTest.php — Simple feature example.
- tests\Browser\ExampleTest.php — Logs in via /wp-login.php and checks for Howdy and the username on the homepage. Requires a valid user.

## License
MIT
