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

class Token {

	/** @var int */
	public int $type;

	/** @var null|string */
	public ?string $contents;

	/**
	 * Token constructor.
	 *
	 * @param string|array $token
	 */
	public function __construct(string|array $token = null) {
		if (is_array($token)) {
			$this->type = (int) $token[0];
			$this->contents = (string) $token[1];
		} else {
			$this->type = -1;
			$this->contents = $token;
		}
	}

	/**
	 * @param TokenVisitorInterface $visitor
	 *
	 * @return mixed
	 */
	public function accept(TokenVisitorInterface $visitor) {
		return $visitor->visitToken($this);
	}
}
