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

class JsonException extends \Exception {
	
	public function __construct(string $message = '', int $code = 0, \Exception $previous = null) {
	    $message = json_last_error_msg();
		parent::__construct($message, $code, $previous);
	}
}
