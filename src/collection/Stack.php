<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\collection;

use Iterator;
use phootwork\lang\parts\PopPart;

/**
 * Represents a Stack
 * 
 * FILO - first in last out
 * 
 * @author Thomas Gossmann
 */
class Stack extends AbstractList {
	use PopPart;

	/**
	 * Creates a new ArrayList
	 * 
	 * @param array|Iterator $collection
	 */
	public function __construct($collection = []) {
		$this->pushAll($collection);
	}

	/**
	 * Pushes an element onto the stack
	 * 
	 * @param mixed $element
	 *
	 * @return $this
	 */
	public function push($element): self {
		array_push($this->array, $element);

		return $this;
	}

	/**
	 * Pushes many elements onto the stack
	 *
	 * @param array|Iterator $collection
	 *
	 * @return $this
	 */
	public function pushAll($collection): self {
		foreach ($collection as $element) {
			$this->push($element);
		}

		return $this;
	}

	/**
	 * Returns the element at the head or null if the stack is empty but doesn't remove that element  
	 * 
	 * @return mixed
	 */
	public function peek() {
		if ($this->size() > 0) {
			return $this->array[$this->size() - 1];
		}

		return null;
	}
}
