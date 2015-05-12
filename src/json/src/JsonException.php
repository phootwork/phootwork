<?php
namespace phootwork\json;

class JsonException extends \Exception {
	
	public function __construct($message = '', $code = 0, \Exception $previous = null) {
		$message = 'Unknown Error';
		
		if (function_exists('json_last_error_msg')) {
			$message = json_last_error_msg();
		} else {
			$messages = [
				Json::ERROR_NONE => 'No error has occurred',
				Json::ERROR_DEPTH => 'Maximum stack depth exceeded',
				Json::ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
				Json::ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
				Json::ERROR_SYNTAX => 'Syntax error',
				Json::ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
				Json::ERROR_RECURSION => 'Recursion detected',
				Json::ERROR_INF_OR_NAN => 'Inf and NaN cannot be JSON encoded',
				Json::ERROR_UNSUPPORTED_TYPE => 'Type is not supported'
			];
			
			if (isset($messages[$code])) {
				$message = $messages[$code];
			}
		}
	
		parent::__construct($message, $code, $previous);
	}
}