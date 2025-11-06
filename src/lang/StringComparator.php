<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\lang;

/**
 * String comparison
 * 
 * @api
 */
class StringComparator implements Comparator {
	#[\Override]
	public function compare(mixed $a, mixed $b): int {
		return strcmp((string) $a, (string) $b);
	}
}
