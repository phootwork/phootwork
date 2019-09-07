<?php declare(strict_types=1);

namespace a\b\c;

class D {
	use Alpha;
	const XYZ = 'xyz';
	public $content;
	public $type;
	public function __construct() {
		// do something
		if ($this->content == self::XYZ) {
			doSomething();
		}
	}
	public function mthd() {
	}
}
