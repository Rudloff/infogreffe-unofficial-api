# Unofficial API for infogreffe.fr

This PHP class allows you to query data on [infogreffe.fr](https://www.infogreffe.fr/societes/).

## How to use it

### Class

You can import `classes/Infogreffe.php` into your PHP code and then use the `Infogreffe::search()` function to retrieve data.

You can also import this library with [Composer](https://getcomposer.org/):

```bash
composer require rudloff/infogreffe-unofficial-api
```

#### Example

```php
use InfogreffeUnofficial\Infogreffe;

require_once __DIR__.'/vendor/autoload.php';

var_dump(Infogreffe::search('foo'));
```

### CLI

There is a basic commandline interface that you can use:

```bash
php cli.php search "Bygmalion"
php cli.php search 13000545700010
```

## How does it work

It uses an undocumented infogreffe.fr REST API.
We are willing to switch to the [Infogreffe open data API](https://datainfogreffe.fr/api/v1/documentation) if and when it includes the same features.
