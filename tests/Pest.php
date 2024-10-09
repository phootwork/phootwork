<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */

use phootwork\file\tests\VfsTestCase;
use phootwork\lang\Arrayable;
use phootwork\tokenizer\tests\TokenizerTestCase;

pest()->extend(VfsTestCase::class)->in('file');
pest()->extend(TokenizerTestCase::class)->in('tokenizer');

/**
 * Custom expectation to test Arrayable objects
 */
expect()->intercept('toBeList', Arrayable::class, function () {
	return expect($this->value->toArray())->toBeList();
});
