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
use phootwork\tokenizer\tests\fixtures\ExampleTokenVisitor;
use phootwork\tokenizer\TokenCollection;

class TokenizerCollectionTest extends TokenizerTest {
    public function testTokenizerCollection(): void {
        $sample = $this->getSample('sample1');

        $tokenizer = new PhpTokenizer();
        $tokens = $tokenizer->tokenize($sample);

        $this->assertTrue($tokens instanceof TokenCollection);
        $this->assertEquals(121, $tokens->size());
    }

    public function testWhitespaceToken(): void {
        $sample = $this->getSample('class');

        $tokenizer = new PhpTokenizer();
        $tokens = $tokenizer->tokenize($sample);

        $this->assertFalse($tokens->get(1)->type == T_WHITESPACE);
    }

    public function testAccept(): void {
        $sample = $this->getSample('class');

        $tokenizer = new PhpTokenizer();
        $tokens = $tokenizer->tokenize($sample);

        $spaceVisitor = new ExampleTokenVisitor();

        $this->assertFalse($tokens->get(1)->accept($spaceVisitor));
    }
}
