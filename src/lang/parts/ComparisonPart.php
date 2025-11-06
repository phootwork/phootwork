<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\lang\parts;

use InvalidArgumentException;
use Stringable;

/**
 * Text methods to perform string and Text object comparison
 *
 * @author Thomas Gossmann
 * @author Cristiano Cinotti
 */
trait ComparisonPart {
	abstract protected function getString(): string;

	/**
	 * Compares this string to another
	 *
	 * @param mixed $comparison
	 *
	 * @throws \TypeError If $comparison is not a string or a Stringableobject.
	 *
	 * @return int
	 *
	 * @see \phootwork\lang\Comparable::compareTo()
	 */
	public function compareTo(mixed $comparison): int {
		if (! $comparison instanceof Stringable && !is_string($comparison)) {
			throw new InvalidArgumentException('A Text object can be compared only with strings or Stringable objects.');
		}

		return $this->compare($comparison);
	}

	/**
	 * Compares this string to another string, ignoring the case
	 *
	 * @param string|Stringable $compare
	 *
	 * @return int Return Values:<br>
	 * 		&lt; 0 if the object is less than comparison<br>
	 *  	&gt; 0 if the object is greater than comparison<br>
	 * 		0 if they are equal.
	 */
	public function compareCaseInsensitive(string|Stringable $compare): int {
		return $this->compare($compare, 'strcasecmp');
	}

	/**
	 * Compares this string to another
	 *
	 * @param string|Stringable   $compare string to compare to
	 * @param callable|null $callback
	 *
	 * @return int
	 *
	 * @psalm-suppress MixedInferredReturnType
	 */
	public function compare(string|Stringable $compare, ?callable $callback = null): int {
		if ($callback === null) {
			$callback = 'strcmp';
		}

		return $callback($this->getString(), (string) $compare);
	}

	/**
	 * Checks whether the string and the given object are equal
	 *
	 * @param string|Stringable $string
	 *
	 * @return bool
	 */
	public function equals(string|Stringable $string): bool {
		return $this->compareTo($string) === 0;
	}

	/**
	 * Checks whether the string and the given object are equal ignoring the case
	 *
	 * @param string|Stringable $string
	 *
	 * @return bool
	 */
	public function equalsIgnoreCase(string|Stringable $string): bool {
		return $this->compareCaseInsensitive($string) === 0;
	}
}
