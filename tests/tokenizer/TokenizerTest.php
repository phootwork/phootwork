<?php
namespace phootwork\tokenizer\tests;

abstract class TokenizerTest extends \PHPUnit_Framework_TestCase {
	
	protected function getSample($file) {
		return file_get_contents(sprintf(__DIR__.'/fixtures/samples/%s.php', $file));
	}

}