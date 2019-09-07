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

use phootwork\collection\Set;
use phootwork\file\exception\FileException;
use phootwork\file\File;
use phootwork\file\Path;
use phootwork\lang\Text;
use phootwork\xml\exception\XmlException;

class XmlParser {

	/**
	 * Controls whether case-folding is enabled for this XML parser. Enabled by default. 
	 * 
	 * Data Type: integer
	 * 
	 * @var int
	 */
	const OPTION_CASE_FOLDING = XML_OPTION_CASE_FOLDING;

	/**
	 * Specify how many characters should be skipped in the beginning of a tag name.
	 * 
	 * Data Type: integer
	 * 
	 * @var int
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

	/** @var resource */
	private $parser;

	/** @var Set */
	private $visitors;

	/**
	 * Creates a new XML parser
	 * 
	 * @param string $encoding Force a specific encoding
	 */
	public function __construct($encoding = 'UTF-8') {
		$this->visitors = new Set();
		$this->parser = xml_parser_create($encoding);

		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, [$this, 'handleElementStart'], [$this, 'handleElementEnd']);
		xml_set_character_data_handler($this->parser, [$this, 'handleCharacterData']);
		xml_set_processing_instruction_handler($this->parser, [$this, 'handleProcessingInstruction']);
		xml_set_notation_decl_handler($this->parser, [$this, 'handleNotationDeclaration']);
		xml_set_unparsed_entity_decl_handler($this->parser, [$this, 'handleUnparsedEntitiyDeclaration']);
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
	public function setOption(int $option, $value): void {
		xml_parser_set_option($this->parser, $option, $value);
	}

	/**
	 * Gets the value for an option
	 * 
	 * @param int $option Any of the XmlParser::OPTION_* constants
	 *
	 * @return mixed
	 */
	public function getOption(int $option) {
		return xml_parser_get_option($this->parser, $option);
	}

	/**
	 * Adds a visitor
	 * 
	 * @param XmlParserVisitorInterface $visitor
	 */
	public function addVisitor(XmlParserVisitorInterface $visitor): void {
		$this->visitors->add($visitor);
	}

	/**
	 * Removes a visitor
	 * 
	 * @param XmlParserVisitorInterface $visitor
	 */
	public function removeVisitor(XmlParserVisitorInterface $visitor): void {
		$this->visitors->remove($visitor);
	}

	/**
	 * Parses a string
	 *
	 * @param string $data
	 *
	 * @throws XmlException
	 */
	public function parse($data): void {
		if (!xml_parse($this->parser, $data)) {
			$code = xml_get_error_code($this->parser);

			throw new XmlException(xml_error_string($code), $code);
		}
	}

	/**
	 * Parses a file
	 *
	 * @param mixed|Path|File|Text|string $file
	 *
	 * @throws XmlException
	 * @throws FileException If something went wrong in reading file
	 */
	public function parseFile($file): void {
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

	/**
	 * @return int
	 */
	private function getCurrentLineNumber(): int {
		return xml_get_current_line_number($this->parser);
	}

	/**
	 * @return int
	 */
	private function getCurrentColumnNumber(): int {
		return xml_get_current_column_number($this->parser);
	}

	/**
	 * handle element start
	 * 
	 * @param resource $parser
	 * @param string $name
	 * @param array $attribs
	 */
	private function handleElementStart($parser, string $name, array $attribs): void {
		/** @var XmlParserVisitorInterface $visitor */
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
	private function handleElementEnd($parser, string $name): void {
		/** @var XmlParserVisitorInterface $visitor */
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
	private function handleCharacterData($parser, string $data): void {
		/** @var XmlParserVisitorInterface $visitor */
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
	private function handleProcessingInstruction($parser, string $target, string $data): void {
		/** @var XmlParserVisitorInterface $visitor */
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
	private function handleNotationDeclaration($parser, string $notationName, string $base, string $systemId,
											   string $publicId): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitNotationDeclaration($notationName, $base, $systemId, $publicId, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}

	/**
	 * handle unparsed entity declaration
	 * 
	 * @param resource $parser
	 * @param string $entityName
	 * @param string $base
	 * @param string $systemId
	 * @param string $publicId
	 * @param string $notationName
	 */
	private function handleUnparsedEntitiyDeclaration($parser, string $entityName, string $base, string $systemId,
													  string $publicId, $notationName): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitUnparsedEntityDeclaration($entityName, $base, $systemId, $publicId, $notationName, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}
}
