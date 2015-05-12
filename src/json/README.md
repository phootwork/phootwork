# PHP JSON Library

[![Build Status](https://travis-ci.org/phootwork/json.svg?branch=master)](https://travis-ci.org/phootwork/json)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phootwork/json/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phootwork/json/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phootwork/json/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phootwork/json/?branch=master)

PHP json library, with clean syntax and proper error management (through exception).

## Installation via Composer

Installation via composer:

```json
{
	"require": {
		"phootwork/json": "~1"
	}
}
```

Then run `composer install`

## Usage

### Constants

Constants can be used through `Json::*`, however they have the same value as their php equivalent.

### Encode

Synopsis:

```php
string Json::encode (mixed $data [, int $options = 0 [, int $depth = 512]])
```

`$depth` works since php 5.5 (is ignored on lower versions).

Example:

```php
use phootwork\json\Json;
use phootwork\json\JsonException;

$data = ['json': 'data'];

try {
	$json = Json::encode($data);
} catch (JsonException $e) {
	// something went wrong
}

var_dump($json);
```

The above example will output:

```php
string(15) "{"json":"data"}"
```

### Decode

By default, this json library returns decoded JSON as array!

Synopsis:

```php
array Json::decode (string $json [, int $options = 0 [, int $depth = 512]])
```

Example:

```php
use phootwork\json\Json;
use phootwork\json\JsonException;

$json = '{"json": "data"}';

try {
	$data = Json::decode($json);
} catch (JsonException $e) {
	// something went wrong
}

var_dump($data);
```

The above example will output:

```php
array(1) {
  'json' =>
  string(4) "data"
}
```
