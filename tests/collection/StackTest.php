<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\collection\tests;

use phootwork\collection\Stack;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase {

	public function testAddRemove(): void {
		$item1 = 'item 1';
		$item2 = 'item 2';
		$item3 = 'item 3';
		$items = [$item1, $item2];

		$stack = new Stack();
		$stack->push($item1);

		$this->assertEquals(1, $stack->size());
		$this->assertEquals($item1, $stack->pop());

		$this->assertEquals(0, $stack->size());

		$stack->pushAll($items);

		$this->assertEquals(2, $stack->size());

		$stack->push($item3);

		$this->assertEquals(3, $stack->size());
	}

	public function testToArray(): void {
		$stack = new Stack(['item 1', 'item 2', 'item 3']);
		$this->assertSame('item 3', $stack->peek());
		$this->assertEquals($stack->toArray(), ['item 1', 'item 2', 'item 3']);

		$stack = new Stack();
		$stack->push('item 1')->push('item 2')->push('item 3');
		$this->assertSame('item 3', $stack->peek());
		$this->assertEquals($stack->toArray(), ['item 1', 'item 2', 'item 3']);

		$stack = new Stack();
		$stack->pushAll(['item 1', 'item 2', 'item 3']);
		$this->assertSame('item 3', $stack->peek());
		$this->assertEquals($stack->toArray(), ['item 1', 'item 2', 'item 3']);
	}

	public function testDuplicateValues(): void {
		$item1 = 'item 1';

		$stack = new Stack();
		$stack->push($item1)->push($item1)->push($item1);

		$this->assertEquals(3, $stack->size());
	}

	public function testOrder(): void {
		$item1 = 'item 1';
		$item2 = 'item 2';
		$item3 = 'item 3';
		$items = [$item1, $item2, $item3];

		$stack = new Stack($items);

		$pops = [];
		$iters = [];

		foreach ($stack as $element) {
			$iters[] = $element;
		}

		while (($item = $stack->pop()) !== null) {
			$pops[] = $item;
		}

		$this->assertSame($iters, array_reverse($pops));

		$stack->clear();
		$this->assertNull($stack->peek());
	}

	public function testContains(): void {
		$item1 = 'item 1';
		$item2 = 'item 2';
		$item3 = 'item 3';
		$items = [$item1, $item2];

		$stack = new Stack($items);

		$this->assertTrue($stack->contains($item2));
		$this->assertFalse($stack->contains($item3));
	}

	public function testClone(): void {
		$stack = new Stack([1, 2, 3, 4, 5, 6]);
		$clone = clone $stack;

		$this->assertTrue($clone instanceof Stack);
		$this->assertEquals($stack, $clone);
		$this->assertEquals($stack->toArray(), $clone->toArray());
		$this->assertNotSame($stack, $clone);
	}

	public function testMap(): void {
		$cb = function ($item) {
			return strtoupper($item);
		};

		$stack = new Stack(['item 1', 'item 2', 'item 3']);
		$this->assertEquals(array_map($cb, $stack->toArray()), $stack->map($cb)->toArray());
	}
}
