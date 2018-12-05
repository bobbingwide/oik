# PHPUnit tests for oik
Unit Tests are not part of the run time deliverables.

# Dependencies

Requires:
- PHPUnit 6.2 or higher
- WordPress 4.9 or higher
- wordpress-develop-tests
- oik-batch - for oik-phpunit.php

Plugins required to be activated:
- oik
- oik-fields 

Syntax:

cd <path-to-wp-content-plugins>oik
pu

where pu invokes

set PHPUNIT=c:\apache\htdocs\phpLibraries\phpunit\phpunit-6.5.13.phar
php ..\..\plugins\oik-batch\oik-phpunit.php "--verbose" "--disallow-test-output" "--stop-on-error" "--stop-on-failure" "--log-junit=phpunit.json" %*

