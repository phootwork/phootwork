<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\tokenizer;

use phootwork\collection\ArrayList;

class TokenCollection extends ArrayList {

	/**
	 * Retrieves a token at the given index
	 * 
	 * @param int $index the given index
	 *
	 * @return Token 
	 */
    public function get(int $index): Token {
        return parent::get($index);
    }
}
