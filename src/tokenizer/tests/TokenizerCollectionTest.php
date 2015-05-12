<?php
namespace phootwork\tokenizer\tests;

use phootwork\tokenizer\PhpTokenizer;
use phootwork\tokenizer\TokenCollection;

class TokenizerCollectionTest extends TokenizerTest {
	
	public function testTokenizerCollection() {
		$sample = $this->getSample('sample1');
		
		$tokenizer = new PhpTokenizer();
		$tokens = $tokenizer->tokenize($sample);
		
		$this->assertTrue($tokens instanceof TokenCollection);
		$this->assertEquals(77, $tokens->size());
	}
	
	public function testWhitespaceToken() {
		$sample = $this->getSample('class');
		
		$tokenizer = new PhpTokenizer();
		$tokens = $tokenizer->tokenize($sample);
		
		$this->assertTrue($tokens->get(1)->type == T_WHITESPACE);
	}
}
