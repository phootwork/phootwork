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

use phootwork\collection\ArrayList;
use phootwork\collection\Collection;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;

class Json {

	/**
	 * No error has occurred.
	 *
	 * Available since PHP 5.3.0.
	 *
	 * @var int
	 */
    const ERROR_NONE = 0;

    /**
     * The maximum stack depth has been exceeded.
     *
     * Available since PHP 5.3.0.
     *
     * @var int
     */
    const ERROR_DEPTH = 1;

    /**
     * Occurs with underflow or with the modes mismatch.
     *
     * Available since PHP 5.3.0.
     *
     * @var int
     */
    const ERROR_STATE_MISMATCH = 2;

    /**
     * Control character error, possibly incorrectly encoded.
     *
     * Available since PHP 5.3.0.
     *
     * @var int
     **/
    const ERROR_CTRL_CHAR = 3;

    /**
     * Syntax error. Available since PHP 5.3.0.
     *
     * @var int
     **/
    const ERROR_SYNTAX = 4;

    /**
     * Malformed UTF-8 characters, possibly incorrectly encoded.
     *
     * This constant is available as of PHP 5.3.3.
     *
     * @var int
     **/
    const ERROR_UTF8 = 5;

    /**
     * The object or array passed to json_encode() include recursive references and
     * cannot be encoded. If the constant PARTIAL_OUTPUT_ON_ERROR option was given, NULL
     * will be encoded in the place of the recursive reference.
     *
     * This constant is available as of PHP 5.5.0.
     *
     * @var int
     **/
    const ERROR_RECURSION = 6;

    /**
     * The value passed to json_encode() includes either NAN or INF. If the constant
     * PARTIAL_OUTPUT_ON_ERROR option was given, 0 will be encoded in the place of
     * these special numbers.
     *
     * This constant is available as of PHP 5.5.0.
     *
     * @var int
     **/
    const ERROR_INF_OR_NAN = 7;

    /**
     * A value of an unsupported type was given to json_encode(), such as a resource. If the
     * constant PARTIAL_OUTPUT_ON_ERROR option was given, NULL will be encoded in the place
     * of the unsupported value.
     *
     * This constant is available as of PHP 5.5.0.
     *
     * @var int
     **/
    const ERROR_UNSUPPORTED_TYPE = 8;

    /**
     * All < and > are converted to \u003C and \u003E.
     *
     * Available since PHP 5.3.0.
     *
     * @var int 1 << 0
     **/
    const HEX_TAG = 1;

    /**
     * All &s are converted to \u0026.
     *
     * Available since PHP 5.3.0.
     *
     * @var int 1 << 1
     **/
    const HEX_AMP = 2;

    /**
     * All ' are converted to \u0027.
     *
     * Available since PHP 5.3.0.
     *
     * @var int 1 << 2
     **/
    const HEX_APOS = 4;

    /**
     * All " are converted to \u0022.
     *
     * Available since PHP 5.3.0.
     *
     * @var int 1 << 3
     **/
    const HEX_QUOT = 8;

    /**
     * Outputs an object rather than an array when a non-associative array is used.
     * Especially useful when the recipient of the output is expecting an object and the
     * array is empty.
     *
     * Available since PHP 5.3.0.
     *
     * @var int 1 << 4
     **/
    const FORCE_OBJECT = 16;

    /**
     * Encodes numeric strings as numbers. Available since PHP 5.3.3.
     *
     * @var int 1 << 5
     **/
    const NUMERIC_CHECK = 32;

    /**
     * Don't escape /. Available since PHP 5.4.0.
     *
     * @var int 1 << 6
     **/
    const UNESCAPED_SLASHES = 64;

    /**
     * Use whitespace in returned data to format it.
     *
     * Available since PHP 5.4.0.
     *
     * @var int 1 << 7 
     **/
    const PRETTY_PRINT = 128;

    /**
     * Encode multibyte Unicode characters literally (default is to escape
     * as \uXXXX).
     *
     * Available since PHP 5.4.0.
     *
     * @var int 1 << 8
     **/
    const UNESCAPED_UNICODE = 256;

    /**
     * 
     * 
     * Available since PHP 5.5.0
     * 
     * @var int 1 << 9
     */
    const PARTIAL_OUTPUT_ON_ERROR = 512;

    /**
     * 
     * @var int 1 << 0
     */
    const OBJECT_AS_ARRAY = 1;

    /**
     * Encodes large integers as their original string value.
     *
     * Available since PHP 5.4.0.
     *
     * @var int 1 << 1
     **/
    const BIGINT_AS_STRING = 2;

    /**
     * Returns the JSON representation of a value
     *
     * @param mixed $data The value being encoded. Can be any type except a resource. All string data must be UTF-8 encoded. 
     * @param int $options Bitmask consisting of HEX_TAG, HEX_AMP, HEX_APOS, HEX_QUOT, FORCE_OBJECT, NUMERIC_CHECK, UNESCAPED_SLASHES, PRETTY_PRINT, UNESCAPED_UNICODE, PARTIAL_OUTPUT_ON_ERROR. 
     * @param int $depth Set the maximum depth. Must be greater than zero. (PHP 5.5 only!)
     *
     * @throws JsonException if something gone wrong
     *
     * @return string Returns a JSON encoded string
     */
    public static function encode($data, int $options = 0, int $depth = 512): string {
        $json = json_encode($data, $options, $depth);

        self::throwExceptionOnError($json);

        return $json;
    }

    /**
     * Decodes a JSON string to an array.
     *
     * @param string $json The json string being decoded. This only works with UTF-8 encoded strings.  
     * @param int $options Bitmask of JSON decode options. Currently only OBJECT_AS_ARRAY, BIGINT_AS_STRING is supported (default is to cast large integers as floats)
     * @param int $depth User specified recursion depth.
     *
     * @throws JsonException if something gone wrong
     *
     * @return array Returns the value encoded in json in appropriate PHP type. Values true, false and null are returned as TRUE, FALSE and NULL respectively. 
     */
    public static function decode(string $json, int $options = 0, int $depth = 512): array {
        $data = json_decode($json, true, $depth, $options);
        self::throwExceptionOnError($data);

        return $data;
    }

    /**
     * Returns a map collection of the provided json
     * 
     * @param string $json
     *
     * @return Map
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
     */
    public static function toCollection(string $json): Collection {
        return CollectionUtils::fromCollection(json_decode($json, true));
    }

    /**
     * @param mixed $output
     *
     * @throws JsonException
     */
    private static function throwExceptionOnError($output): void {
        $error = json_last_error();

        if ($output === null || $error !== self::ERROR_NONE) {
            throw new JsonException('', $error);
        }
    }
}
