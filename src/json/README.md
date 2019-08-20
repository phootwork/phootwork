# PHP JSON Library

[![Build Status](https://travis-ci.org/phootwork/phootwork.svg?branch=master)](https://travis-ci.org/phootwork/phootwork)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phootwork/phootwork/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phootwork/phootwork/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phootwork/phootwork/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phootwork/phootwork/?branch=master)
[![License](https://img.shields.io/github/license/phootwork/json.svg?style=flat-square)](https://packagist.org/packages/phootwork/json)
[![Latest Stable Version](https://img.shields.io/packagist/v/phootwork/json.svg?style=flat-square)](https://packagist.org/packages/phootwork/json)
[![Total Downloads](https://img.shields.io/packagist/dt/phootwork/json.svg?style=flat-square&colorB=007ec6)](https://packagist.org/packages/phootwork/json)<br>

PHP json library, with clean syntax and proper error management (through exception).

## Goals

- Wrap native PHP functions with classes
- Provide solid error handling with exceptions

## Installation

Installation via composer:

```
composer require phootwork/json
```

## Documentation

[https://phootwork.github.io/json](https://phootwork.github.io/json)

## Running tests

This package is a part of the Phootwork library. In order to run the test suite, you have to download the full library.

```
git clone https://github.com/phootwork/phootwork
```
Then install the dependencies via composer:

```
composer install
```
Now, run the *json* test suite:

```
vendor/bin/phpunit --testsuite json
```
If you want to run the whole library tests, simply run:

```
vendor/bin/phpunit
```


## Contact

Report issues at the github [Issue Tracker](https://github.com/phootwork/phootwork/issues).

## Changelog

Refer to [Releases](https://github.com/phootwork/phootwork/releases)