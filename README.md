SERPS cli
=========

Command line utility to scrape from the terminal

Install
-------

You can install it globally with composer:

```sh
composer global require serps/cli
```

Test installation by invoking the help message

```sh
serps help
```

Google
------

### Search

Search command allows to trigger a google search from the command line

``serps google:search "keyword"``

#### example

```php
    $ serps google:search "github"
    {
      "natural-results": [
        {
          "url": "https://github.com/",
          "title": "How people build software · GitHub",
          "types": [
            "classical",
            "classical_large"
          ]
        },
        {
          "url": "https://fr.wikipedia.org/wiki/GitHub",
          "title": "GitHub — Wikipédia",
          "types": [
            "classical"
          ]
        },
        {
          "url": "https://en.wikipedia.org/wiki/GitHub",
          "title": "GitHub - Wikipedia, the free encyclopedia",
          "types": [
            "classical"
          ]
        },
        {
          "url": "http://rue89.nouvelobs.com/2015/03/31/quest-tous-les-techos-monde-font-github-258439",
          "title": "Qu'est-ce que tous les techos du monde font sur GitHub ? - Rue89 - L ...",
          "types": [
            "classical"
          ]
        },
        {
          "url": "https://twitter.com/github?lang=fr",
          "title": "GitHub (@github) | Twitter",
          "types": [
            "classical"
          ]
        },
        {
          "url": "https://www.githubarchive.org/",
          "title": "GitHub Archive",
          "types": [
            "classical"
          ]
        },
        {
          "url": "https://wiki.jenkins-ci.org/display/JENKINS/GitHub+Plugin",
          "title": "GitHub Plugin - Jenkins - Jenkins Wiki",
          "types": [
            "classical"
          ]
        }
      ],
      "total-count": 1.31e+08,
      "natural-results-count": 7,
      "evaluated": true,
      "http-client": "curl",
      "url": "https://www.google.fr/search?q=github&gws_rd=cr&ei=kH7OV7LaForeU_yGhtgC",
      "initial-url": "https://google.com/search?q=github"
    }
```

Tip: In the example [jq](https://stedolan.github.io/jq/) helped to pretty format the outputted json:
``$ serps google:search "github" | jq '.'``



#### Advanced usage

```php
    $ serps google:search --tld="co.uk" --lr="lang_es" "some keywords" phantomjs
```

With proxy:


```php
    $ serps google:search --proxy="http://proxy:8080" "some keywords"
```

Page and result per page:

```php
    $ serps google:search --page=2 --res-per-page=20 "some keywords"
```


Dump page in a file:

In a addition of printing the results you can save the dom in a file

```php
    $ serps google:search --dump="/path/to/file.html" "some keywords"
```

By default the dump option wont be able to process if the file you specify already exists. The option force-dump
makes it able to override an existing file:

```php
    $ serps google:search --dump="/path/to/file.html" --force-dump=true "some keywords"
```


Read and parse a html google file saved locally instead of doing http query


```php
    $ serps google:search --file=./google--search-file.html "some keywords"
```
