# PHPUnit tests for oik
Unit Tests are not part of the run time deliverables.
These tests were written to test oik internationalization.
See [How I’m testing the internationalization and localization of my WordPress plugins](https://herbmiller.me/test-internationalization-localization-wordpress-plugins/)

# Dependencies

Requires:
- PHPUnit 6.2 or higher
- WordPress 4.9 or higher
- wordpress-develop-tests
- oik-batch - for oik-phpunit.php
- WordPress language files for the bb_BB locale

Plugins required to be activated:
- oik
- oik-fields 
- oik-css
- us-tides
- oik-user

Themes to be available:
- genesis-oik 

Syntax:

cd <path-to-wp-content-plugins>oik
pu

where pu invokes

set PHPUNIT=c:\apache\htdocs\phpLibraries\phpunit\phpunit-6.5.13.phar
php ..\..\plugins\oik-batch\oik-phpunit.php "--verbose" "--disallow-test-output" "--stop-on-error" "--stop-on-failure" "--log-junit=phpunit.json" %*

