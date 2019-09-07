<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\xml\tests;

use phootwork\collection\Stack;
use phootwork\xml\XmlParserNoopVisitor;

class StackParserVisitor extends XmlParserNoopVisitor {

	/** @var Stack */
	private $elementStack;

	public function __construct() {
		$this->elementStack = new Stack();
	}

	/**
	 * @return Stack
	 */
	public function getElementStack(): Stack {
		return $this->elementStack;
	}

	public function visitElementStart(string $name, array $attributes, int $line, int $column): void {
		$this->elementStack->push($name);
	}
}
