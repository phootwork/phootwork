<?php
namespace phootwork\tokenizer;

interface TokenVisitorInterface {
	public function visitToken(Token $token);
}
