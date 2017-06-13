<?php
namespace phootwork\xml\tests;

use phootwork\xml\XmlParserNoopVisitor;
use phootwork\collection\Stack;

class StackParserVisitor extends XmlParserNoopVisitor {
	
	/** @var Stack */
	private $elementStack;

	public function __construct() {
		$this->elementStack = new Stack();
	}
	
	/**
	 * @return Stack
	 */
	public function getElementStack() {
		return $this->elementStack;
	}
	
	public function visitElementStart($name, $attributes) {
		$this->elementStack->push($name);
	}
}