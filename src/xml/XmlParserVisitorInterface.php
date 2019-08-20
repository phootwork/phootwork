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

interface XmlParserVisitorInterface {

	/**
	 * visits the start of an element
	 *
	 * @param string $name       contains the name of the element
	 * @param array  $attributes contains an associative array with the element's attributes
	 * @param int    $column     column number
	 * @param int    $line       line number
	 */
	public function visitElementStart(string $name, array $attributes, int $line, int $column): void;
	
	/**
	 * visits the end of an element
	 * 
	 * @param string $name contains the name of the element
	 * @param int $line line number
	 * @param int $column column number
	 */
	public function visitElementEnd(string $name, int $line, int $column): void;
	
	/**
	 * visits character data
	 * 
	 * @param string $data
	 * @param int $line line number
	 * @param int $column column number
	 */
	public function visitCharacterData(string $data, int $line, int $column): void;
	
	/**
	 * visits a processing instruction
	 * 
	 * @param string $target contains the PI target
	 * @param string $data contains the PI data
	 * @param int $line line number
	 * @param int $column column number
	 */
	public function visitProcessingInstruction(string $target, string $data, int $line, int $column): void;
	
	/**
	 * visits a notation declaration
	 *
	 * @param string $notationName the notations name
	 * @param string $base
	 * @param string $systemId system identifier of the external notation declaration
	 * @param string $publicId public identifier of the external notation declaration
	 * @param int $line line number
	 * @param int $column column number
	 */
	public function visitNotationDeclaration(string $notationName, string $base, string $systemId, string $publicId,
											 int $line, int $column): void;
	
	/**
	 * visits an unparsed entity declaration
	 *
	 * @param string $entityName the name of the entity that is about to be defined
	 * @param string $base
	 * @param string $systemId system identifier for the external entity
	 * @param string $publicId public identifier for the external entity
	 * @param string $notationName name of the notation of this entity
	 * @param int $line line number
	 * @param int $column column number
	 */
	public function visitUnparsedEntityDeclaration(string $entityName, string $base, string $systemId,
													string $publicId, string $notationName, int $line, int $column): void;
}
