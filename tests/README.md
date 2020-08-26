# PHPUnit tests for oik
Unit Tests are not part of the run time deliverables.
These tests were written to test oik internationalization.
See [How I’m testing the internationalization and localization of my WordPress plugins](https://herbmiller.me/test-internationalization-localization-wordpress-plugins/)

# Dependencies

Requires:
- PHPUnit 8.4.1 or higher
- WordPress 5.5 or higher
- wordpress-develop-tests
- oik-batch - for oik-phpunit.php
- WordPress language files for the bb_BB locale

Plugins required to be activated:
- oik
- oik-fields 
- oik-css
- us-tides
- oik-user
- oik-bob-bing-wide

Themes to be available:
- genesis-oik 

Syntax:

```
cd <path-to-wp-content-plugins>oik
pu
```

where `pu` invokes

```
set PHPUNIT=c:\apache\htdocs\phpLibraries\phpunit\phpunit-8.4.1.phar
php ..\..\plugins\oik-batch\oik-phpunit.php "--verbose" "--disallow-test-output" "--stop-on-error" "--stop-on-failure" "--log-junit=phpunit.json" %*
```

Prior to running the tests run

```
prepu
```

where `prepu` attempts to prepare the environment for running PHPUnit tests.
It uses `WP-cli` to activate the required plugins and sets the language to `en_GB`

## Notes
In order for the tests to run to completion in multiple environments some settings must have particular values:

- oik-css: Disable automatic paragraph creation should be unchecked
- SCRIPT_DEBUG needs to be true
- wp-mail-smtp: should not be activated
- oik: PayPal country should be set to: United Kingdom

These settings allow the PhpUnit tests for oik-libs to run to completion as well.

