This my personal skeleton for a new php library. 

Features
--------

* phpunit structure
* preset composer
* Fair Licence
* phpcs for psr-2 + phpcbf for psr-2 commands
* CI build configured with
    * travis-ci configured
    * composer and xdebug fix
    * code climate configured with code coverage for master branch only
* editorconfig default 
* gitinogre default
* php minal version is 5.5.0


Steps to setup
--------------

- run ``composer create-project --no-install --prefer-source gsouf/skeleton path``
- Edit ``composer.json``
- run ``composer update``
- setup travis with the codeclimate token
- update ``src/Library.php``
- write code in ``src``
- update ``tests/suites/Library.php``
- write tests in ``test/suites``
- update ``CONTRIBUTING.md``
- update ``README.md``
- push everything
