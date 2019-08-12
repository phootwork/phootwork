<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */

namespace phootwork\json\tests;

use phootwork\collection\ArrayList;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;
use phootwork\json\Json;
use PHPUnit\Framework\TestCase;

/**
 * Test class partly taken from Simon Hampel: https://bitbucket.org/hampel/json
 */
class CollectionTest extends TestCase {
	
	public function testMap(): void {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
		$json = Json::encode($data);
		$map = Json::toMap($json);

		$this->assertTrue($map instanceof Map);
		$this->assertEquals($data, $map->toArray());
	}
	
	public function testList(): void {
		$data = [1, 2, 4];
		$json = Json::encode($data);
		$list = Json::toList($json);
	
		$this->assertTrue($list instanceof ArrayList);
		$this->assertEquals($data, $list->toArray());
	}
	
	public function testComplex(): void {
		$data = ['a' => 1, 'b' => [1, 2, 4], 'c' => 3, 'd' => 4, 'e' => 5];
		
		$json = Json::encode($data);
		$map = Json::toMap($json);
		
		$this->assertTrue($map instanceof Map);
		$this->assertTrue($map->get('b') instanceof ArrayList);
		$this->assertEquals($data, CollectionUtils::toArrayRecursive($map));
	}

	public function testToCollection(): void {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
		$json = Json::encode($data);
		$map = Json::toCollection($json);

		$this->assertTrue($map instanceof Map);
		$this->assertEquals($data, $map->toArray());

		$data = [1, 2, 4];
		$json = Json::encode($data);
		$list = Json::toCollection($json);

		$this->assertTrue($list instanceof ArrayList);
		$this->assertEquals($data, $list->toArray());
	}
}
