# Kompakt Audio Tools

Wrapper for some audio command line tools

## Install

+ `git clone https://github.com/kompakt/audio-tools.git`
+ `cd audio-tools`
+ `curl -sS https://getcomposer.org/installer | php`
+ `php composer.phar install`

## Tests

+ `cp tests/config.php.dist config.php`
+ Adjust `config.php` as needed
+ `vendor/bin/phpunit`
+ `vendor/bin/phpunit --coverage-html tests/_coverage`

## License

See LICENSE.