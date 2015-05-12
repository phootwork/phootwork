<?php
namespace phootwork\tokenizer;

use phootwork\collection\ArrayList;

class TokenCollection extends ArrayList {

	/**
	 * Retrieves a token at the given index
	 * 
	 * @param int $index the given index
	 * @return Token 
	 */
	public function get($index) {
		return parent::get($index);
	}

}