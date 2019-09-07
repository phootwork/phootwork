<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\tokenizer\tests\fixtures;

use phootwork\tokenizer\Token;
use phootwork\tokenizer\TokenVisitorInterface;

class ExampleTokenVisitor implements TokenVisitorInterface {
	public function visitToken(Token $token): bool {
		if ($token->type === T_WHITESPACE) {
			return true;
		}

		return false;
	}
}
