<?php
namespace phootwork\json\tests;

use phootwork\collection\ArrayList;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;
use phootwork\json\Json;

/**
 * Test class partly taken from Simon Hampel: https://bitbucket.org/hampel/json
 */
class CollectionTest extends \PHPUnit_Framework_TestCase {
	
	public function testMap() {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
		$json = Json::encode($data);
		$map = Json::toMap($json);

		$this->assertTrue($map instanceof Map);
		$this->assertEquals($data, $map->toArray());
	}
	
	public function testList() {
		$data = [1, 2, 4];
		$json = Json::encode($data);
		$list = Json::toList($json);
	
		$this->assertTrue($list instanceof ArrayList);
		$this->assertEquals($data, $list->toArray());
	}
	
	public function testComplex() {
		$data = ['a' => 1, 'b' => [1, 2, 4], 'c' => 3, 'd' => 4, 'e' => 5];
		
		$json = Json::encode($data);
		$map = Json::toMap($json);
		
		$this->assertTrue($map instanceof Map);
		$this->assertTrue($map->get('b') instanceof ArrayList);
		$this->assertEquals($data, CollectionUtils::toArrayRecursive($map));
	}

}
