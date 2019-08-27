# Phootwork library

[![Build Status](https://travis-ci.org/phootwork/phootwork.svg?branch=master)](https://travis-ci.org/phootwork/phootwork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phootwork/phootwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phootwork/phootwork/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phootwork/phootwork/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phootwork/phootwork/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/a873cc250773621aa74b/maintainability)](https://codeclimate.com/github/phootwork/phootwork/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/a873cc250773621aa74b/test_coverage)](https://codeclimate.com/github/phootwork/phootwork/test_coverage)
[![License](https://img.shields.io/github/license/phootwork/phootwork.svg?style=flat-square)](https://packagist.org/packages/phootwork/phootwork)

Phootwork is a collection of php libraries which fill gaps in the php language and provides consistent object oriented solutions
where the language natively offers only functions.

The phootwork package includes:

- [collection](https://github.com/phootwork/collection) a library to model several flavours of collections
- [file](https://github.com/phootwork/file) an object oriented library to manipulate filesystems elements (*stream compatible*)
- [json](https://github.com/phootwork/json) a json library, with clean syntax and proper error handling
- [lang](https://github.com/phootwork/lang) a library to manipulate arrays and strings in an object oriented way
- [tokenizer](https://github.com/phootwork/tokenizer) an easy to use tokenizer library for PHP code
- [xml](https://github.com/phootwork/xml) an object oriented xml utility library

## Installation

We use [composer](https://getcomposer.org) as dependency manager and distribution system. To install the library run:

```bash
composer require phootwork/phootwork
```

Each single package can be installed separately. I.e. if you want to include in your project the `collection` library only:

```bash
composer require phootwork/collection
```

> **Note**: the single library packages does not ship with tests and --dev dependencies. If you want to run the test suite or
> contribute to the library, you have to install the whole `phootwork/phootwork` package.

## A Little Taste

The following examples show what you can find in this library. You can discover much, much more by reading the documentation
and the api.

### A Little Taste of *lang* Library (`phootwork\lang\Text` class);

```php
<?php declare(strict_types=1);
/**
 * Example describing how to manipulate a string via the Text class
 * and its nice fluent api.
 */
use phootwork\lang\Text;

$text = new Text('a beautiful string');

// Remove the substring 'a ' and capitalize. Note: Text objects are *immutable*, 
// so you should assign the result to a variable
$text = $text->slice(2)->toCapitalCase(); // 'Beautiful string'

// Capitalize each word and add an 's' character at the end of the string
$text = $text->toCapitalCaseWords()->append('s'); // 'Beautiful Strings'

// Calculate the length of the string
$length = $text->length(); // 17

// Check if the string ends with the 'ngs' substring
$text->endsWith('ngs'); // true
```
### A Little Taste of *collection* Library (`phootwork\collection\Stack` class)

```php
<?php declare(strict_types=1);
/**
 * Example describing how to manipulate a Stack collection (Last In First Out)
 * via the Stack class
 */
use phootwork\collection\Stack;

$stack = new Stack(['Obiwan', 'Luke', 'Yoda', 'Leila']);

// Sort the stack
$stack = $stack->sort(); // ['Leila', 'Luke', 'Obiwan', 'Yoda']

// Check if the collection contains any elements
$stack->isEmpty();  // false

// How many elements?
$stack->size(); // 4

// Push an elememt
$stack->push('Chewbecca');

// How many elements now?
$stack->size(); // 5

// Peek the head element (return the head element, without removing it)
$stack->peek(); // 'Chewbecca'
$stack->size(); // 5

// Pop the head element
$stack->pop(); // 'Chewbecca'
$stack->size(); // 4: pop() removes the popped element
```

## Documentation

The official documentation site: [https://phootwork.github.io](https://phootwork.github.io)

## Running Tests

In order to run the test suite, download the full library:

```
git clone https://github.com/phootwork/phootwork
```
Then install the dependencies via composer:

```
composer install
```
and run:

```
composer test
```
Our `test` script calls the `vendor/bin/phpunit` command under the hood, so you can pass to it all the phpunit options,
via `--` operator i.e.: `composer test -- --stop-on-failure`.

Each library has its own test suite and you can run it separately. I.e. suppose you want to run the collection library
test suite:

```
composer test -- --testsuite collection
```
or alternatively:
```
vendor/bin/phpunit --testsuite collection
```

Phootwork also provides a command to generate a code coverage report in html format, into the `coverage/` directory:
```
composer coverage
```

## Contact

Report issues at the github [Issue Tracker](https://github.com/phootwork/phootwork/issues).


## Contributing

Every contribute is welcome, whether it is a simple typo or a new modern complicated feature. We are very grateful to all
the people who will dedicate their precious time to this library!

You can find all information about it in the [CONTRIBUTING.md](CONTRIBUTING.md) document.


## Changelog

Refer to [Releases](https://github.com/phootwork/phootwork/releases)
