<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\tokenizer\tests;

use phootwork\tokenizer\PhpTokenizer;
use phootwork\tokenizer\Token;

class TokenCollectionTest extends TokenizerTest {
	public function testGetMethod(): void {
		$sample = $this->getSample('class');

		$tokenizer = new PhpTokenizer();
		$tokens = $tokenizer->tokenize($sample);

		// the native PHP function `token_get_all` considers the string "a\b\c" as
		// one single token in PHP 8 and 5 different tokens in PHP < 8
		phpversion() < '8.0.0' ?
			$this->assertEquals(99, $tokens->size(), 'The fixture class has 99 tokens') :
			$this->assertEquals(95, $tokens->size(), 'The fixture class has 95 tokens')
		;
		$this->assertInstanceOf(Token::class, $tokens->get(55));
		$this->assertNull($tokens->get(500));
	}
}
