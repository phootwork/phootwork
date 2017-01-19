<?php
namespace phootwork\xml;

use phootwork\collection\Set;
use phootwork\xml\exception\XmlException;

class XmlParser {
	
	private $parser;
	
	public function __construct($encoding = null) {
		$this->visitors = new Set();
		$this->parser = xml_parser_create($encoding);
		
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'handleElementStart', 'handleElementEnd');
		xml_set_character_data_handler($this->parser, 'handleCharacterData');
		xml_set_processing_instruction_handler($this->parser, 'handleProcessingInstruction');
		xml_set_notation_decl_handler($this->parser, 'handleNotationDeclaration');
		xml_set_unparsed_entity_decl_handler($this->parser, 'handleUnparsedEntitiyDeclaration');
	}
	
	public function __destruct() {
		xml_parser_free($this->parser);
	}
	
	public function setOption($option, $value) {
		xml_parser_set_option($option, $value);
	}
	
	public function getOption($option) {
		return xml_parser_get_option($this->parser, $option);
	}
	
	/**
	 * Adds a visitor
	 * 
	 * @param XmlParserVisitorInterface $visitor
	 */
	public function addVisitor(XmlParserVisitorInterface $visitor) {
		$this->visitors->add($visitor);
	}
	
	/**
	 * Removes a visitor
	 * 
	 * @param XmlParserVisitorInterface $visitor
	 */
	public function removeVisitor(XmlParserVisitorInterface $visitor) {
		$this->visitors->remove($visitor);
	}
	
	/**
	 * 
	 * @param string $data
	 */
	public function parse($data) {
		if (!xml_parse($this->parser, $data)) {
			$code = xml_get_error_code($this->parser);
			throw new XmlException(xml_error_string($code), $code);
		}
	}
	
	/**
	 * handle element start
	 * 
	 * @param resource $parser
	 * @param string $name
	 * @param array $attribs
	 */
	private function handleElementStart($parser, $name, $attribs) {
		/** @var $visitor XmlParserVisitorInterface */
		foreach ($this->visitors as $visitor) {
			$visitor->visitElementStart($name, $attribs);
		}
	}
	
	/**
	 * handle element end 
	 *
	 * @param resource $parser
	 * @param string $name
	 */
	private function handleElementEnd($parser, $name) {
		/** @var $visitor XmlParserVisitorInterface */
		foreach ($this->visitors as $visitor) {
			$visitor->visitElementEnd($name);
		}
	}
	
	/**
	 * handle cdata
	 * 
	 * @param resource $parser
	 * @param string $data
	 */
	private function handleCharacterData($parser, $data) {
		/** @var $visitor XmlParserVisitorInterface */
		foreach ($this->visitors as $visitor) {
			$visitor->visitCharacterData($data);
		}
	}
	
	/**
	 * handle processing instruction
	 * 
	 * @param resource $parser
	 * @param string $target
	 * @param string $data
	 */
	private function handleProcessingInstruction($parser, $target, $data) {
		/** @var $visitor XmlParserVisitorInterface */
		foreach ($this->visitors as $visitor) {
			$visitor->visitProcessingInstruction($target, $data);
		}
	}
	
	/**
	 * handle notation declaration
	 * 
	 * @param resource $parser
	 * @param string $notationName
	 * @param string $base
	 * @param string $systemId
	 * @param string $publicId
	 */
	private function handleNotationDeclaration($parser, $notationName, $base, $systemId, $publicId) {
		/** @var $visitor XmlParserVisitorInterface */
		foreach ($this->visitors as $visitor) {
			$visitor->visitNotationDeclaration($notationName, $base, $systemId, $publicId);
		}
	}
	
	/**
	 * handle unparsed entitiy declaration
	 * 
	 * @param resource $parser
	 * @param string $entityName
	 * @param string $base
	 * @param string $systemId
	 * @param string $publicId
	 * @param string $notationName
	 */
	private function handleUnparsedEntitiyDeclaration($parser, $entityName, $base, $systemId, $publicId, $notationName) {
		/** @var $visitor XmlParserVisitorInterface */
		foreach ($this->visitors as $visitor) {
			$visitor->visitUnparsedEntitiyDeclaration($entityName, $base, $systemId, $publicId, $notationName);
		}
	}
}
