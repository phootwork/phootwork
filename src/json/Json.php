<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\json;

use JsonException;
use phootwork\collection\ArrayList;
use phootwork\collection\Collection;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;

class Json {
	/**
	 * Returns the JSON representation of a value
	 *
	 * @param mixed $data The value being encoded. Can be any type except a resource. All string data must be UTF-8 encoded.
	 * @param int $options Bitmask consisting of JSON_FORCE_OBJECT, JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
	 *                     JSON_HEX_APOS, JSON_INVALID_UTF8_IGNORE, JSON_INVALID_UTF8_SUBSTITUTE, JSON_NUMERIC_CHECK,
	 *                     JSON_PARTIAL_OUTPUT_ON_ERROR, JSON_PRESERVE_ZERO_FRACTION, JSON_PRETTY_PRINT,
	 *                     JSON_UNESCAPED_LINE_TERMINATORS, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE
	 * @param int $depth Set the maximum depth. Must be greater than zero.
	 *
	 * @throws JsonException if something gone wrong
	 *
	 * @return string Returns a JSON encoded string
	 *
	 * @see https://www.php.net/manual/en/json.constants.php for constant details
	 */
	public static function encode(mixed $data, int $options = 0, int $depth = 512): string {
		return json_encode($data, $options | JSON_THROW_ON_ERROR, $depth);
	}

	/**
	 * Decodes a JSON string to an array.
	 *
	 * @param string $json The json string being decoded. This only works with UTF-8 encoded strings.  
	 * @param int $options Bitmask consisting of JSON_FORCE_OBJECT, JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
	 *                     JSON_HEX_APOS, JSON_INVALID_UTF8_IGNORE, JSON_INVALID_UTF8_SUBSTITUTE, JSON_NUMERIC_CHECK,
	 *                     JSON_PARTIAL_OUTPUT_ON_ERROR, JSON_PRESERVE_ZERO_FRACTION, JSON_PRETTY_PRINT,
	 *                     JSON_UNESCAPED_LINE_TERMINATORS, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE
	 * @param int $depth User specified recursion depth.
	 *
	 * @throws JsonException if something gone wrong
	 *
	 * @return array Returns the value encoded in json in appropriate PHP type. Values true, false and null
	 *               are returned as TRUE, FALSE and NULL respectively.
	 *
	 *
	 * @psalm-suppress MixedReturnStatement if `json_decode` doesn't return an array, a `TypeError` exception
	 *                 is thrown, which fits for us
	 * @psalm-suppress MixedInferredReturnType
	 */
	public static function decode(string $json, int $options = 0, int $depth = 512): array {
		return json_decode($json, true, $depth, $options | JSON_THROW_ON_ERROR);
	}

	/**
	 * Returns a map collection of the provided json
	 * 
	 * @param string $json
	 *
	 * @return Map
	 *
	 * @psalm-suppress MixedArgument `json_decode($json, true)` returns an array
	 */
	public static function toMap(string $json): Map {
		return CollectionUtils::toMap(json_decode($json, true));
	}

	/**
	 * Returns a list collection of the provided json
	 *
	 * @param string $json
	 *
	 * @return ArrayList
	 *
	 * @psalm-suppress MixedArgument `json_decode($json, true)` returns an array
	 */
	public static function toList(string $json): ArrayList {
		return CollectionUtils::toList(json_decode($json, true));
	}

	/**
	 * Returns a collection (list or map) of the provided json
	 *
	 * @param string $json
	 *
	 * @return Collection
	 *
	 * @psalm-suppress MixedArgument `json_decode($json, true)` returns an array
	 */
	public static function toCollection(string $json): Collection {
		return CollectionUtils::fromCollection(json_decode($json, true));
	}
}
