<?php
namespace phootwork\xml;

interface XmlParserVisitorInterface {

	/**
	 * visits the start of an element
	 * 
	 * @param string $name contains the name of the element
	 * @param array $attributes contains an associative array with the element's attributes
	 * @paran interger $line line number
	 * @param integer $column column number
	 */
	public function visitElementStart($name, $attributes, $line, $column);
	
	/**
	 * visits the end of an element
	 * 
	 * @param string $name contains the name of the element
	 * @paran interger $line line number
	 * @param integer $column column number
	 */
	public function visitElementEnd($name, $line, $column);
	
	/**
	 * visits character data
	 * 
	 * @param string $data
	 * @paran interger $line line number
	 * @param integer $column column number
	 */
	public function visitCharacterData($data, $line, $column);
	
	/**
	 * visits a processing instruction
	 * 
	 * @param string $target contains the PI target
	 * @param string $data contains the PI data
	 * @paran interger $line line number
	 * @param integer $column column number
	 */
	public function visitProcessingInstruction($target, $data, $line, $column);
	
	/**
	 * visits a notation declaration
	 *
	 * @param string $notationName the notations name
	 * @param string $base
	 * @param string $systemId system identifier of the external notation declaration
	 * @param string $publicId public identifier of the external notation declaration
	 * @paran interger $line line number
	 * @param integer $column column number
	 */
	public function visitNotationDeclaration($notationName, $base, $systemId, $publicId, $line, $column);
	
	/**
	 * visits an unparsed entitiy declaration
	 *
	 * @param string $entityName the name of the entity that is about to be defined
	 * @param string $base
	 * @param string $systemId system identifier for the external entity
	 * @param string $publicId public identifier for the external entity
	 * @param string $notationName name of the notation of this entity
	 * @paran interger $line line number
	 * @param integer $column column number
	 */
	public function visitUnparsedEntitiyDeclaration($entityName, $base, $systemId, $publicId, $notationName, $line, $column);
	
}
