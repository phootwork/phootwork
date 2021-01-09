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

		$this->assertEquals(95, $tokens->size(), 'The fixture class has 95 tokens');
		$this->assertInstanceOf(Token::class, $tokens->get(55));
		$this->assertNull($tokens->get(500));
	}
}
