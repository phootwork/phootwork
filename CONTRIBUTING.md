# Phootwork Contributing Guide

If you're reading this document, it is likely that you have decided to contribute to this project.
Therefore, first of all, we want to warmly thank you! The Open Source Software grows and improves
thanks to people like you!

Contributing to a project is a process with its own rules and we try to explain them along this document.

## Quick Start for Experienced Programmers

1. Fork, clone and apply your patches. See the [directory structure](#understanding-the directory-structure) explanation
if needed and don't forget to write tests.
2. Run the test suite `composer test` and fix all red tests.
3. Run static analysis tool (by now, we use [Psalm](https://psalm.dev/)) `composer analytics` and fix all errors and issues.
4. Fix the coding standard `composer cs-fix`.

Phootwork also provides a command to run all the tests and analytics, required for a pull request:
```
composer check
```

## Requirements

To contribute to this project, you should have an good knowledge of PHP (of course) and a basic knowledge of
[git](https://git-scm.com/), [Github](https://github.com/) and the dependency manager [composer](https://getcomposer.org/). 

## First Step: Clone the Project and Install the Dependencies

The phootwork project is a collection of single libraries that can be installed separately. Anyway, all the development
process takes place in the global repository https://github.com/phootwork/phootwork .

So, the first step is to fork the [phootwork repository](https://github.com/phootwork/phootwork) on Github and clone it
on your local machine. If you have any doubt, please read https://help.github.com/en/articles/fork-a-repo .

Then install the dependencies:
```bash
composer install
```

## Understanding the Directory Structure

After cloning and installing the dependencies, you should have a directory structure like the following:
```.
   ├── bin
   ├── src
   │   ├── collection
   │   ├── file
   │   │   └── exception
   │   ├── json
   │   ├── lang
   │   │   └── text
   │   ├── tokenizer
   │   └── xml
   │       └── exception
   ├── tests
   │   ├── collection
   │   │   └── fixtures
   │   ├── file
   │   ├── json
   │   ├── lang
   │   │   ├── fixtures
   │   │   └── text
   │   ├── tokenizer
   │   │   └── fixtures
   │   │       └── samples
   │   └── xml
   │       └── fixtures
   └── vendor
```

Into the directory `bin` we have two scripts: `auto_split.sh` splits the whole Phootwork repository into the single
library packages and `build_api.sh` generates the api documentation. You usually don't care about them, because they are
run by our continuous integration server [Travis ci](https://travis-ci.org).

As usual, into the directory `vendor` you can find all the libraries Phootwork depends on, both for runtime and development.

The directory `src` contains the source code of our libraries and `tests` contains the relative tests.

Both `src` and `tests` have sub-folders which correspond to the single libraries. E.g. you can find the source code of
the library https://github.com/phootwork/lang into `src/lang` folder and the relative tests into `tests/lang` directory. 
 
## Running the Test Suite

While developing, the test part is very important: if you apply a patch to the existing code, the test suite must run without
errors or failures and if you add a new functionality, no one will consider it without tests.

Our test tool is [PhpUnit](https://phpunit.de/) and we provide a script to launch it:

```bash
composer test
```
Since our command runs phpunit binary under the hood, you can pass all phpunit options to it via the `--` operator, i.e.:
```bash
composer test -- --stop-on-failure
```
You can also use phpunit directly:
```
vendor/bin/phpunit
```

Each single library has its own test suite and you can launch it separately. I.e. if you want to run the `phootwork/lang`
test suite:

```bash
composer test -- --testsuite lang
```
or alternatively:
```bash
vendor/bin/phpunit --testsuite lang
```

The last two commands can be useful to speed up the tests, if your contribution involves only one library.

Phootwork also provides a command to generate a code coverage report in html format, into the `coverage/` directory:
```
composer coverage
```

## Static Analysis Tool

To prevent as many bugs as possible, we use a static analysis tool called [Psalm](https://psalm.dev/).
To launch it, run the following command from the root directory of Phootwork project:

```bash
composer analytics
```

After its analysis, Psalm outputs errors and issues with its suggestions on how to fix them. Errors are more important
and generally more dangerous than issues, anyway you should fix both.

## Coding Standard

Phootwork ships its scripts to easily fix coding standard errors, via [php-cs-fixer](https://cs.symfony.com/) tool.
To fix coding standard errors just run:

```bash
composer cs-fix
```
and to show the errors without fixing them, run:
```bash
composer cs
```
If you want to learn more about phootwork code style, see https://github.com/phootwork/php-cs-fixer-config.

## Icing on the Cake

Phootwork provides a script to run all the previous explained commands in a single line:
```bash
composer check
```
It runs all the tests, analytics and code fixers needed before submitting a pull request.
