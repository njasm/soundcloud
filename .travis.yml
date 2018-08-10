language: php

dist: trusty

php:
  - 5.6
  - 7
  - 7.1
  - 7.2
  - hhvm

matrix:
  fast_finish: true
  allow_failures:
    - php: 5.6
    - hhvm
    
before_script:
  #- echo $TRAVIS_PHP_VERSION
  #- dpkg -l | grep 'php5\|curl' && php --version
  - travis_retry composer self-update
  - travis_retry composer install --prefer-source --no-interaction --dev
  - if [[ "$TRAVIS_PHP_VERSION" != "hhvm" ]]; then mkdir -p build/logs; fi
  
script:
  - vendor/bin/phpunit -v -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
  
after_success:
  # coveralls.io
  - if [[ "$TRAVIS_PHP_VERSION" != "hhvm" ]]; then php vendor/bin/coveralls -v; fi
  # scrutinizer-ci
  - if [[ "$TRAVIS_PHP_VERSION" != "hhvm" ]]; then wget https://scrutinizer-ci.com/ocular.phar; fi
  - if [[ "$TRAVIS_PHP_VERSION" != "hhvm" ]]; then php ocular.phar code-coverage:upload --format=php-clover build/logs/clover.xml; fi
  # codecov
  - if [[ "$TRAVIS_PHP_VERSION" != "hhvm" ]]; then bash <(curl -s https://codecov.io/bash) -f "build/logs/clover.xml"; fi
