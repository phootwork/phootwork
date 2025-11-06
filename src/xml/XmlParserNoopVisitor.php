<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\xml;

/**
 * @psalm-suppress ClassMustBeFinal We want this class to be extensible.
 */
class XmlParserNoopVisitor implements XmlParserVisitorInterface {
	/**
	 * @param string $name
	 * @param int    $line
	 * @param int    $column
	 */
	#[\Override]
	public function visitElementEnd(string $name, int $line, int $column): void {
	}

	/**
	 * @param string $target
	 * @param string $data
	 * @param int    $line
	 * @param int    $column
	 */
	#[\Override]
	public function visitProcessingInstruction(string $target, string $data, int $line, int $column): void {
	}

	/**
	 * @param string $notationName
	 * @param string $base
	 * @param string $systemId
	 * @param string $publicId
	 * @param int    $line
	 * @param int    $column
	 */
	#[\Override]
	public function visitNotationDeclaration(
		string $notationName,
		string $base,
		string $systemId,
		string $publicId,
		int $line,
		int $column
	): void {
	}

	/**
	 * @param string $entityName
	 * @param string $base
	 * @param string $systemId
	 * @param string $publicId
	 * @param string $notationName
	 * @param int    $line
	 * @param int    $column
	 */
	#[\Override]
	public function visitUnparsedEntityDeclaration(
		string $entityName,
		string $base,
		string $systemId,
		string $publicId,
		string $notationName,
		int $line,
		int $column
	): void {
	}

	/**
	 * @param string $name
	 * @param array  $attributes
	 * @param int    $line
	 * @param int    $column
	 */
	#[\Override]
	public function visitElementStart(string $name, array $attributes, int $line, int $column): void {
	}

	/**
	 * @param string $data
	 * @param int    $line
	 * @param int    $column
	 */
	#[\Override]
	public function visitCharacterData(string $data, int $line, int $column): void {
	}
}
