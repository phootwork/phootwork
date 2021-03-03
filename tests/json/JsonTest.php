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

use JsonException;
use phootwork\json\Json;
use PHPUnit\Framework\TestCase;

/**
 * Test class partly taken from Simon Hampel: https://bitbucket.org/hampel/json
 */
class JsonTest extends TestCase {
	public function testEncode(): void {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
		$this->assertEquals(json_encode($data), Json::encode($data));
	}

	public function testEncodeNoException(): void {
		$data = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];

		try {
			Json::encode($data);
			$this->assertTrue(true, 'Test pass');
		} catch (JsonException $e) {
			$this->assertNotEquals(JSON_ERROR_NONE, $e->getCode());
		}
	}

	public function testEncodeWithOptions(): void {
		$data = ['<foo>', "'bar'", '"baz"', '&blong&', "\xc3\xa9"];

		$bitmask = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeWithObject(): void {
		$data = [[1, 2, 3]];

		$bitmask = JSON_FORCE_OBJECT;

		$this->assertEquals(json_encode($data, $bitmask), Json::encode($data, $bitmask));
	}

	public function testEncodeBroken(): void {
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Malformed UTF-8 characters, possibly incorrectly encoded');
		$this->expectExceptionCode(JSON_ERROR_UTF8);

		Json::encode([pack('H*', 'c32e')]);
	}

	public function testDecode(): void {
		$data = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
		$this->assertEquals(json_decode($data, true), Json::decode($data));
	}

	public function testDecodeNoException(): void {
		$json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

		try {
			Json::decode($json);
			$this->assertTrue(true, 'Test pass');
		} catch (JsonException $e) {
			$this->assertNotEquals(JSON_ERROR_NONE, $e->getCode());
		}
	}

	public function testDecodeBrokenSyntaxError(): void {
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Syntax error');
		$this->expectExceptionCode(JSON_ERROR_SYNTAX);

		$badJson = "{ 'bar': 'baz' }";
		Json::decode($badJson);
	}

	public function testDecodeBrokenStackDepth(): void {
		$this->expectException(JsonException::class);
		$this->expectExceptionMessage('Maximum stack depth exceeded');
		$this->expectExceptionCode(JSON_ERROR_DEPTH);

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
}
