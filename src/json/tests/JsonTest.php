<?php
namespace phootwork\json\tests;

use phootwork\json\Json;
use phootwork\json\JsonException;

/**
 * Test class partly taken from Simon Hampel: https://bitbucket.org/hampel/json
 */
class JsonTest extends \PHPUnit_Framework_TestCase {
	
	public function testEncode() {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
		$this->assertEquals(json_encode($data), Json::encode($data));
	}
	
	public function testEncodeNoException() {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
		
		try {
			Json::encode($data);
		} catch (JsonException $e) {
			$this->assertNotEquals(Json::ERROR_NONE, $e->getCode());
		}
	}

	public function testEncodeWithOptions() {
		$data = ['<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9"];

		$bitmask = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeWithObject() {
		$data = [[1,2,3]];

		$bitmask = JSON_FORCE_OBJECT;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	/**
	 * @expectedException \phootwork\json\JsonException
	 * @expectedExceptionCode \phootwork\json\Json::ERROR_UTF8
	 */
	public function testEncodeBroken() {
		Json::encode([pack("H*" ,'c32e')]);
	}

	public function testDecode() {
		$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
		$this->assertEquals(json_decode($data, true), Json::decode($data));
	}
	
	public function testDecodeNoException() {
		$json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
	
		try {
			Json::decode($json);
		} catch (JsonException $e) {
			$this->assertNotEquals(Json::ERROR_NONE, $e->getCode());
		}
	}

	/**
	 * @expectedException \phootwork\json\JsonException
	 * @expectedExceptionCode \phootwork\json\Json::ERROR_SYNTAX
	 */
	public function testDecodeBrokenSyntaxError() {
		$badJson = "{ 'bar': 'baz' }";
		Json::decode($badJson);
	}

	/**
	 *
	 * @expectedException \phootwork\json\JsonException
	 * @expectedExceptionCode \phootwork\json\Json::ERROR_DEPTH
	 */
	public function testDecodeBrokenStackDepth() {
		$json = json_encode([
				1 => [
					'English' => [
						'One',
						'January'
					],
					'French' => [
						'Une',
						'Janvier'
					]
				]
			]);

		Json::decode($json, 0, 3);
	}
	
	public function testConstants() {
		$this->assertEquals(JSON_ERROR_CTRL_CHAR, Json::ERROR_CTRL_CHAR);
		$this->assertEquals(JSON_ERROR_DEPTH, Json::ERROR_DEPTH);
		$this->assertEquals(JSON_ERROR_NONE, Json::ERROR_NONE);
		$this->assertEquals(JSON_ERROR_STATE_MISMATCH, Json::ERROR_STATE_MISMATCH);
		$this->assertEquals(JSON_ERROR_SYNTAX, Json::ERROR_SYNTAX);
		$this->assertEquals(JSON_ERROR_UTF8, Json::ERROR_UTF8);
		
		$this->assertEquals(JSON_BIGINT_AS_STRING, Json::BIGINT_AS_STRING);
		$this->assertEquals(JSON_FORCE_OBJECT, Json::FORCE_OBJECT);
		$this->assertEquals(JSON_HEX_AMP, Json::HEX_AMP);
		$this->assertEquals(JSON_HEX_APOS, Json::HEX_APOS);
		$this->assertEquals(JSON_HEX_QUOT, Json::HEX_QUOT);
		$this->assertEquals(JSON_HEX_TAG, Json::HEX_TAG);
		$this->assertEquals(JSON_NUMERIC_CHECK, Json::NUMERIC_CHECK);
		$this->assertEquals(JSON_OBJECT_AS_ARRAY, Json::OBJECT_AS_ARRAY);
		
		$this->assertEquals(JSON_PRETTY_PRINT, Json::PRETTY_PRINT);
		$this->assertEquals(JSON_UNESCAPED_SLASHES, Json::UNESCAPED_SLASHES);
		$this->assertEquals(JSON_UNESCAPED_UNICODE, Json::UNESCAPED_UNICODE);
		
		if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
			$this->assertEquals(JSON_ERROR_RECURSION, Json::ERROR_RECURSION);
			$this->assertEquals(JSON_ERROR_INF_OR_NAN, Json::ERROR_INF_OR_NAN);
			$this->assertEquals(JSON_ERROR_UNSUPPORTED_TYPE, Json::ERROR_UNSUPPORTED_TYPE);
			$this->assertEquals(JSON_PARTIAL_OUTPUT_ON_ERROR, Json::PARTIAL_OUTPUT_ON_ERROR);
		}
	}
}
