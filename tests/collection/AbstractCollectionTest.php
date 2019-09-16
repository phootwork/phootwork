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

use phootwork\collection\ArrayList;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;
use PHPUnit\Framework\TestCase;

class AbstractCollectionTest extends TestCase {
	public function testSize(): void {
		$list = new ArrayList();

		$this->assertTrue($list->isEmpty());
		$this->assertEquals(0, $list->size());

		$list->add('item 1')->add('item 2');

		$this->assertFalse($list->isEmpty());
		$this->assertEquals(2, $list->size());

		$list->clear();

		$this->assertTrue($list->isEmpty());
		$this->assertEquals(0, $list->size());

		$list = new ArrayList(['item 1', 'item 2']);

		$this->assertFalse($list->isEmpty());
		$this->assertEquals(2, $list->size());
	}

	public function testIterator(): void {
		$data = ['item 1', 'item 2'];
		$list = new ArrayList($data);
		$elements = [];
		$keyelems = [];
		$counter = 0;

		foreach ($list as $element) {
			$elements[] = $element;
			$counter++;
		}

		foreach ($list as $key => $element) {
			$keyelems[$key] = $element;
		}

		$this->assertEquals(2, $counter);
		$this->assertSame($data, $elements);
		$this->assertSame($elements, $keyelems);
	}

	public function testIteratorAssociative(): void {
		$data = ['item_1' => 'Item 1', 'item_2' => 'Item 2'];
		$map = new Map($data);
		$elements = [];
		$keyElements = [];
		$counter = 0;

		foreach ($map as $key => $element) {
			$keyElements[$key] = $element;
			$counter++;
		}

		foreach ($map as $element) {
			$elements[] = $element;
		}

		$this->assertEquals(2, $counter);
		$this->assertCount(2, $elements);
		$this->assertSame($data, $keyElements);
		$this->assertSame(array_values($data), $elements);
	}

	public function testExport(): void {
		$data = [1, 2, ['a' => 'b', 'c' => [7, 8, 9]], 4];
		$list = CollectionUtils::fromCollection($data);
		$this->assertEquals(4, count($list->toArray()));

		$data = ['a' => 'b', 'c' => [1, ['x' => 'y'], 4], 'd' => 'e'];
		$map = CollectionUtils::fromCollection($data);
		$this->assertEquals(3, count($map->toArray()));
	}
}
