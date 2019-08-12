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

use phootwork\collection\CollectionUtils;
use phootwork\collection\ArrayList;
use phootwork\collection\Map;
use phootwork\collection\tests\fixtures\DummyIteratorClass;
use PHPUnit\Framework\TestCase;

class CollectionUtilsTest extends TestCase {

	public function testList(): void {
		$data = [1, 2, 4];

		$list = CollectionUtils::fromCollection($data);

		$this->assertTrue($list instanceof ArrayList);
	}

	public function testMap(): void {
		$data = ['a' => 'b', 'c' => 'd'];

		$map = CollectionUtils::fromCollection($data);

		$this->assertTrue($map instanceof Map);
	}

	public function testComplex(): void {
		$data = [['a' => 'b'], ['c' => 'd']];
		$list = CollectionUtils::fromCollection($data);

		$this->assertTrue($list instanceof ArrayList);
		$this->assertTrue($list->get(1) instanceof Map);

		$data = ['a' => [1, 2, 3], 'c' => 'd'];
		/** @var Map $map */
		$map = CollectionUtils::fromCollection($data);

		$this->assertTrue($map instanceof Map);
		$this->assertTrue($map->get('a') instanceof ArrayList);


		$data = ['a' => 'b', 'c' => [1, ['x' => 'y'], 4], 'd' => 'e'];
		/** @var Map $map */
		$map = CollectionUtils::fromCollection($data);
		$this->assertTrue($map->get('c')->get(1) instanceof Map);
	}

	public function testCollectionFromCollection(): void {
		$list = new ArrayList([1, 2, 3]);
		$coll = CollectionUtils::fromCollection($list);

		$this->assertEquals($list, $coll);
	}

	public function testInvalidArgument(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('$collection is neither an array nor an iterator');

		CollectionUtils::fromCollection(1);
	}

	public function testToMap(): void {
		$data = [['a' => 'b'], ['c' => 'd'], [1, 2, 3]];
		$map = CollectionUtils::toMap($data);

		$this->assertTrue($map instanceof Map);
		$this->assertTrue($map->get(0) instanceof Map);
		$this->assertTrue($map->get(2) instanceof ArrayList);

		$map = new Map($data);
		$this->assertFalse($map->get(0) instanceof Map);
		$this->assertFalse($map->get(2) instanceof ArrayList);
	}

	public function testToList(): void {
		$data = ['a' => 'b', 'c' => [1, ['x' => 'y'], 4], 'd' => ['x' => 'y', 'z' => 'zz']];
		$list = CollectionUtils::toList($data);

		$this->assertTrue($list instanceof ArrayList);
		$this->assertEquals('b', $list->get(0));
		$this->assertTrue($list->get(2) instanceof Map);

		$list = new ArrayList($data);
		$this->assertEquals('b', $list->get(0));
		$this->assertFalse($list->get(2) instanceof Map);
	}

	public function testNonsense(): void {
		$dummy = new DummyIteratorClass(range(10, 20));

		$map = CollectionUtils::fromCollection($dummy);

		$this->assertTrue($map instanceof Map);
	}

	public function testToRecursiveArray(): void {
		$data = ['a' => 'b', 'c' => [1, ['x' => 'y'], 4], 'd' => ['x' => 'y', 'z' => 'zz']];
		$collection = CollectionUtils::fromCollection($data);

		$this->assertEquals($data, CollectionUtils::toArrayRecursive($collection));
	}
}
