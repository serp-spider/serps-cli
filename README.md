Status Monitor
==============

**This is a work in progress**

Set of tools to check that SERPS is uptodate with search engines.



Google
------

### Search

Search command allows to trigger a google search from the command line, the purpose is to have fast debug/testing
access to serps api.

Usage:

```php
    $ serps google search "some keywords"
```


Advanced usage:

```php
    $ serps google search --tld="co.uk" --lr="lang_es" "some keywords" phantomjs
```
