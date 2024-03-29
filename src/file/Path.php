<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\file;

use phootwork\lang\ArrayObject;
use phootwork\lang\Text;
use Stringable;

class Path implements Stringable {
	/** @var ArrayObject */
	private ArrayObject $segments;

	/** @var string */
	private string $stream = '';

	/** @var Text */
	private Text $pathname;

	/** @var string */
	private string $dirname;

	/** @var string */
	private string $filename;

	/** @var string */
	private string $extension;

	/**
	 * Path constructor.
	 *
	 * @param string|Stringable $pathname
	 */
	public function __construct(Stringable|string $pathname) {
		$this->pathname = new Text($pathname);

		if ($this->pathname->match('/^[a-zA-Z]+:\/\//')) {
			$this->stream = $this->pathname->slice(0, (int) $this->pathname->indexOf('://') + 3)->toString();
			$this->pathname = $this->pathname->substring((int) $this->pathname->indexOf('://') + 3);
		}

		$this->segments = $this->pathname->split('/');
		$this->extension = pathinfo($this->pathname->toString(), PATHINFO_EXTENSION);
		$this->filename = basename($this->pathname->toString());
		$this->dirname = dirname($this->pathname->toString());
	}

	/**
	 * Returns the extension
	 * 
	 * @return Text the extension
	 */
	public function getExtension(): Text {
		return new Text($this->extension);
	}

	/**
	 * Returns the filename
	 *
	 * @return Text the filename
	 */
	public function getFilename(): Text {
		return new Text($this->filename);
	}

	/**
	 * Gets the path without filename
	 *
	 * @return Text
	 */
	public function getDirname(): Text {
		return new Text($this->stream . $this->dirname);
	}

	/**
	 * Gets the full pathname
	 *
	 * @return Text
	 */
	public function getPathname(): Text {
		return new Text($this->stream . $this->pathname);
	}

	/**
	 * @return bool
	 */
	public function isStream(): bool {
		return ('' !== $this->stream);
	}

	/**
	 * Changes the extension of this path
	 *
	 * @param string|Stringable $extension the new extension
	 *
	 * @return self
	 */
	public function setExtension(Stringable|string $extension): self {
		$pathinfo = pathinfo($this->pathname->toString());

		$pathname = new Text($pathinfo['dirname']);
		if (!empty($pathinfo['dirname'])) {
			$pathname = $pathname->append('/');
		}

		return new self(
			$pathname
				->append($pathinfo['filename'])
				->append('.')
				->append((string) $extension)
		);
	}

	/**
	 * Returns a path with the same segments as this path but with a 
	 * trailing separator added (if not already existent).
	 * 
	 * @return $this
	 */
	public function addTrailingSeparator(): self {
		if (!$this->hasTrailingSeparator()) {
			$this->pathname = $this->pathname->append('/');
		}

		return $this;
	}

	/**
	 * Returns the path obtained from the concatenation of the given path's
	 * segments/string to the end of this path.
	 *
	 * @param string|Stringable $path
	 *
	 * @return Path
	 */
	public function append(Stringable|string $path): self {
		if ($path instanceof self) {
			$path = $path->getPathname();
		}

		if (!$this->hasTrailingSeparator()) {
			$this->addTrailingSeparator();
		}

		return new self($this->getPathname()->append($path));
	}

	/**
	 * Returns whether this path has a trailing separator.
	 * 
	 * @return bool
	 */
	public function hasTrailingSeparator(): bool {
		return $this->pathname->endsWith('/');
	}

	/**
	 * Returns whether this path is empty
	 * 
	 * @return bool
	 */
	public function isEmpty(): bool {
		return $this->pathname->isEmpty();
	}

	/**
	 * Returns whether this path is an absolute path.
	 * 
	 * @return bool
	 */
	public function isAbsolute(): bool {
		//Stream urls are always absolute
		if ($this->isStream()) {
			return true;
		}

		if (realpath($this->pathname->toString()) == $this->pathname->toString()) {
			return true;
		}

		if ($this->pathname->length() == 0 || $this->pathname->startsWith('.')) {
			return false;
		}

		// Windows allows absolute paths like this.
		if ($this->pathname->match('#^[a-zA-Z]:\\\\#')) {
			return true;
		}

		// A path starting with / or \ is absolute; anything else is relative.
		return $this->pathname->startsWith('/') || $this->pathname->startsWith('\\');
	}

	/**
	 * Checks whether this path is the prefix of another path
	 * 
	 * @param Path $anotherPath
	 *
	 * @return bool
	 */
	public function isPrefixOf(self $anotherPath): bool {
		return $anotherPath->getPathname()->startsWith($this->pathname);
	}

	/**
	 * Returns the last segment of this path, or null if it does not have any segments.
	 * 
	 * @return Text
	 */
	public function lastSegment(): Text {
		/** @var string[] $this->segments */
		return new Text($this->segments[count($this->segments) - 1]);
	}

	/**
	 * Makes the path relative to another given path
	 * 
	 * @param Path $base
	 *
	 * @return Path the new relative path
	 */
	public function makeRelativeTo(self $base): self {
		$pathname = clone $this->pathname;

		return new self($pathname->replace($base->removeTrailingSeparator()->getPathname(), ''));
	}

	/**
	 * Returns a count of the number of segments which match in this 
	 * path and the given path, comparing in increasing segment number order.
	 * 
	 * @param Path $anotherPath
	 *
	 * @return int
	 */
	public function matchingFirstSegments(self $anotherPath): int {
		$segments = $anotherPath->segments();
		$count = 0;
		/**
		 * @var int $i
		 * @var string $segment
		 */
		foreach ($this->segments as $i => $segment) {
			if ($segment != $segments[$i]) {
				break;
			}
			$count++;
		}

		return $count;
	}

	/**
	 * Returns a new path which is the same as this path but with the file extension removed.
	 * 
	 * @return Path
	 */
	public function removeExtension(): self {
		return new self($this->pathname->replace('.' . $this->getExtension(), ''));
	}

	/**
	 * Returns a copy of this path with the given number of segments removed from the beginning.
	 * 
	 * @param int $count
	 *
	 * @return Path
	 */
	public function removeFirstSegments(int $count): self {
		$segments = new ArrayObject();
		for ($i = $count; $i < $this->segmentCount(); $i++) {
			$segments->append($this->segments[$i]);
		}

		return new self($segments->join('/'));
	}

	/**
	 * Returns a copy of this path with the given number of segments removed from the end.
	 * 
	 * @param int $count
	 *
	 * @return Path
	 */
	public function removeLastSegments(int $count): self {
		$segments = new ArrayObject();
		for ($i = 0; $i < $this->segmentCount() - $count; $i++) {
			$segments->append($this->segments[$i]);
		}

		return new self($segments->join('/'));
	}

	/**
	 * Returns a copy of this path with the same segments as this path but with a trailing separator removed.
	 * 
	 * @return $this
	 */
	public function removeTrailingSeparator(): self {
		if ($this->hasTrailingSeparator()) {
			$this->pathname = $this->pathname->substring(0, -1);
		}

		return $this;
	}

	/**
	 * Returns the specified segment of this path, or null if the path does not have such a segment.
	 *
	 * @param int $index
	 *
	 * @return string|null
	 */
	public function segment(int $index): ?string {
		/** @var string[] $this->segments */
		return $this->segments[$index] ?? null;
	}

	/**
	 * Returns the number of segments in this path.
	 * 
	 * @return int
	 */
	public function segmentCount(): int {
		return $this->segments->count();
	}

	/**
	 * Returns the segments in this path in order.
	 * 
	 * @return ArrayObject
	 */
	public function segments(): ArrayObject {
		return $this->segments;
	}

	/**
	 * Returns a FileDescriptor corresponding to this path.
	 * 
	 * @return FileDescriptor
	 */
	public function toFileDescriptor(): FileDescriptor {
		return new FileDescriptor($this->getPathname());
	}

	/**
	 * Returns a string representation of this path
	 * 
	 * @return string A string representation of this path
	 */
	public function toString(): string {
		return $this->stream . $this->pathname;
	}

	/**
	 * String representation as pathname
	 */
	public function __toString(): string {
		return $this->toString();
	}

	/**
	 * Returns a copy of this path truncated after the given number of segments.
	 * 
	 * @param int $count
	 *
	 * @return Path
	 */
	public function upToSegment(int $count): self {
		$segments = new ArrayObject();
		for ($i = 0; $i < $count; $i++) {
			$segments->append($this->segments[$i]);
		}

		return new self($segments->join('/'));
	}

	/**
	 * Checks whether both paths point to the same location
	 *
	 * @param Path|string $anotherPath
	 *
	 * @return bool true if the do, false if they don't
	 */
	public function equals(self|string $anotherPath): bool {
		$anotherPath = new self($anotherPath);

		if ($this->isStream() xor $anotherPath->isStream()) {
			return false;
		}

		if ($this->isStream() && $anotherPath->isStream()) {
			return $this->toString() === $anotherPath->toString();
		}

		return realpath($this->pathname->toString()) == realpath($anotherPath->toString());
	}
}
