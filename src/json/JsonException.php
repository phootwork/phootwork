<?php
namespace phootwork\json;

class JsonException extends \Exception {
	
	public function __construct($message = '', $code = 0, \Exception $previous = null) {
	    $message = json_last_error_msg();
		parent::__construct($message, $code, $previous);
	}
}