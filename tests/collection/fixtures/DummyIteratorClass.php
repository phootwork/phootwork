<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\collection\tests\fixtures;

class DummyIteratorClass implements \Iterator {
	private array $collection;

	public function __construct(array $contents = []) {
		$this->collection = $contents;
	}

	/**
	 * @internal
	 */
	#[\ReturnTypeWillChange]
	public function rewind(): mixed {
		return reset($this->collection);
	}

	/**
	 * @internal
	 */
	public function current(): mixed {
		return current($this->collection);
	}

	/**
	 * @internal
	 */
	#[\ReturnTypeWillChange]
	public function key() {
		return key($this->collection);
	}

	/**
	 * @internal
	 */
	#[\ReturnTypeWillChange]
	public function next(): mixed {
		return next($this->collection);
	}

	/**
	 * @internal
	 */
	public function valid(): bool {
		return key($this->collection) !== null;
	}
}
