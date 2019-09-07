<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\lang\tests\fixtures;

use phootwork\lang\Comparable;

class Item implements Comparable {

	/** @var string string */
	private $content;

	public function __construct(string $content = '') {
		$this->content = $content;
	}

	public function compareTo($comparison): int {
		return strcmp($this->content, $comparison->getContent());
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param $content
	 *
	 * @return $this
	 */
	public function setContent($content): self {
		$this->content = $content;

		return $this;
	}
}
