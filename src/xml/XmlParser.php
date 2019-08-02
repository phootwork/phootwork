<?php
namespace phootwork\xml;

use phootwork\collection\Set;
use phootwork\xml\exception\XmlException;
use phootwork\file\Path;
use phootwork\file\File;
use phootwork\lang\Text;

class XmlParser {
	
	/**
	 * Controls whether case-folding is enabled for this XML parser. Enabled by default. 
	 * 
	 * Data Type: integer
	 * 
	 * @var integer
	 */
	const OPTION_CASE_FOLDING = XML_OPTION_CASE_FOLDING;
	
	/**
	 * Specify how many characters should be skipped in the beginning of a tag name.
	 * 
	 * Data Type: integer
	 * 
	 * @var integer
	 */
	const OPTION_SKIP_TAGSTART = XML_OPTION_SKIP_TAGSTART;
	
	/**
	 * Whether to skip values consisting of whitespace characters. 
	 * 
	 * Data Type: integer
	 * 
	 * @var string
	 */
	const OPTION_SKIP_WHITE = XML_OPTION_SKIP_WHITE;
	
	/**
	 * Sets which target encoding to use in this XML parser. By default, it is set to the same as the 
	 * source encoding used by XmlParser::construct(). Supported target encodings are ISO-8859-1, US-ASCII and UTF-8.
	 * 
	 * Data Type: string
	 *
	 * @var string
	 */
	const OPTION_TARGET_ENCODING = XML_OPTION_TARGET_ENCODING;
	
	
	private $parser;
	
	/** @var Set */
	private $visitors;
	
	/**
	 * Creates a new XML parser
	 * 
	 * @param string $encoding Force a specific encoding
	 */
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
	
	/**
	 * Set an option for the parser
	 * 
	 * @param int $option Any of the XmlParser::OPTION_* constants
	 * @param mixed $value The desired value
	 */
	public function setOption($option, $value) {
		xml_parser_set_option($this->parser, $option, $value);
	}
	
	/**
	 * Gets the value for an option
	 * 
	 * @param int $option Any of the XmlParser::OPTION_* constants
	 * @return mixed
	 */
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
	 * Parses a string
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
	 * Parses a file
	 * 
	 * @param Path|File|Text|string $file
	 */
	public function parseFile($file) {
		if ($file instanceof Path) {
			$file = $file->getPathname();
		}
		
		if ($file instanceof Text) {
			$file = $file->toString();
		}
		
		if (is_string($file)) {
			$file = new File($file);
		}
		
		if ($file instanceof File) {
			$this->parse($file->read());
		}
	}
	
	private function getCurrentLineNumber() {
		return xml_get_current_line_number($this->parser);
	}
	
	private function getCurrentColumnNumber() {
		return xml_get_current_column_number($this->parser);
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
			$visitor->visitElementStart(strtolower($name), $attribs, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
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
			$visitor->visitElementEnd(strtolower($name), $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
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
			$visitor->visitCharacterData($data, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
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
			$visitor->visitProcessingInstruction($target, $data, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
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
			$visitor->visitNotationDeclaration($notationName, $base, $systemId, $publicId, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
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
			$visitor->visitUnparsedEntitiyDeclaration($entityName, $base, $systemId, $publicId, $notationName, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}
}
