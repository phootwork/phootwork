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
use phootwork\xml\exception\XmlException;
use Stringable;
use XmlParser as BaseParser;

class XmlParser {

	/**
	 * Controls whether case-folding is enabled for this XML parser. Enabled by default. 
	 * 
	 * Data Type: integer
	 * 
	 * @var int
	 */
	public const OPTION_CASE_FOLDING = XML_OPTION_CASE_FOLDING;

	/**
	 * Specify how many characters should be skipped in the beginning of a tag name.
	 * 
	 * Data Type: integer
	 * 
	 * @var int
	 */
	public const OPTION_SKIP_TAGSTART = XML_OPTION_SKIP_TAGSTART;

	/**
	 * Whether to skip values consisting of whitespace characters. 
	 * 
	 * Data Type: integer
	 * 
	 * @var string
	 */
	public const OPTION_SKIP_WHITE = XML_OPTION_SKIP_WHITE;

	/**
	 * Sets which target encoding to use in this XML parser. By default, it is set to the same as the 
	 * source encoding used by XmlParser::construct(). Supported target encodings are ISO-8859-1, US-ASCII and UTF-8.
	 * 
	 * Data Type: string
	 *
	 * @var string
	 */
	public const OPTION_TARGET_ENCODING = XML_OPTION_TARGET_ENCODING;

	/** @var BaseParser */
	private BaseParser $parser;

	/** @var Set */
	private Set $visitors;

	/**
	 * Creates a new XML parser
	 * 
	 * @param string $encoding Force a specific encoding
	 *
	 * @psalm-suppress InvalidPropertyAssignmentValue Psalm issue: in PHP8 the function `xml_parser_create`
	 *                 returns an `XmlParser` class. Remove it when fixed.
	 */
	public function __construct(string $encoding = 'UTF-8') {
		$this->visitors = new Set();
		$this->parser = xml_parser_create($encoding);

		xml_set_element_handler($this->parser, [$this, 'handleElementStart'], [$this, 'handleElementEnd']);
		xml_set_character_data_handler($this->parser, [$this, 'handleCharacterData']);
		xml_set_processing_instruction_handler($this->parser, [$this, 'handleProcessingInstruction']);
		xml_set_notation_decl_handler($this->parser, [$this, 'handleNotationDeclaration']);
		xml_set_unparsed_entity_decl_handler($this->parser, [$this, 'handleUnparsedEntitiyDeclaration']);
	}

	/**
	 * Set an option for the parser
	 * 
	 * @param int $option Any of the XmlParser::OPTION_* constants
	 * @param mixed $value The desired value
	 */
	public function setOption(int $option, mixed $value): bool {
		return xml_parser_set_option($this->parser, $option, $value);
	}

	/**
	 * Gets the value for an option
	 * 
	 * @param int $option Any of the XmlParser::OPTION_* constants
	 *
	 * @return mixed
	 *
	 * @psalm-suppress InvalidArgument Psalm issue: in PHP8 the function `xml_parser_get_option`
	 *                  expects a `XmlParser` and not more `resource`. Remove it when fixed.
	 */
	public function getOption(int $option): mixed {
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
	 * @param string|Stringable $data
	 *
	 * @throws XmlException
	 *
	 */
	public function parse(string|Stringable $data): void {
		if (!xml_parse($this->parser, (string) $data)) {
			$code = xml_get_error_code($this->parser);

			throw new XmlException(xml_error_string($code), $code);
		}
	}

	/**
	 * Parses a file
	 *
	 * @param string|Stringable $file
	 *
	 * @throws XmlException
	 * @throws FileException If something went wrong in reading file
	 */
	public function parseFile(string|Stringable $file): void {
		$file = new File($file);
		$this->parse($file->read());
	}

	/**
	 * @return int
	 * @psalm-suppress InvalidArgument Psalm issue: in PHP8 the function `xml_get_current_line_number`
	 *                  expects a `XmlParser` and not more `resource`. Remove it when fixed.
	 */
	private function getCurrentLineNumber(): int {
		return xml_get_current_line_number($this->parser);
	}

	/**
	 * @return int
	 * @psalm-suppress InvalidArgument Psalm issue: in PHP8 the function `xml_get_current_column_number`
	 *                  expects a `XmlParser` and not more `resource`. Remove it when fixed.
	 */
	private function getCurrentColumnNumber(): int {
		return xml_get_current_column_number($this->parser);
	}

	/**
	 * handle element start
	 * 
	 * @param BaseParser $parser
	 * @param string $name
	 * @param array $attribs
	 *
	 * @psalm-suppress UnusedParam xml_parse() function in self::parse(),
	 *                 call this method passing $parser as the first parameter
	 */
	private function handleElementStart(BaseParser $parser, string $name, array $attribs): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitElementStart(strtolower($name), $attribs, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}

	/**
	 * handle element end 
	 *
	 * @param BaseParser $parser
	 * @param string $name
	 *
	 * @psalm-suppress UnusedParam xml_parse() function in self::parse(),
	 *                 call this method passing $parser as the first parameter
	 */
	private function handleElementEnd(BaseParser $parser, string $name): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitElementEnd(strtolower($name), $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}

	/**
	 * handle cdata
	 *
	 * @param BaseParser $parser
	 * @param string $data
	 *
	 * @psalm-suppress UnusedParam xml_parse() function in self::parse(),
	 *                 call this method passing $parser as the first parameter
	 */
	private function handleCharacterData(BaseParser $parser, string $data): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitCharacterData($data, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}

	/**
	 * handle processing instruction
	 *
	 * @param BaseParser $parser
	 * @param string     $target
	 * @param string     $data
	 *
	 * @psalm-suppress UnusedParam xml_parse() function in self::parse(),
	 *                 call this method passing $parser as the first parameter
	 */
	private function handleProcessingInstruction(BaseParser $parser, string $target, string $data): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitProcessingInstruction($target, $data, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}

	/**
	 * handle notation declaration
	 *
	 * @param BaseParser $parser
	 * @param string     $notationName
	 * @param string     $base
	 * @param string     $systemId
	 * @param string     $publicId
	 *
	 * @psalm-suppress UnusedParam xml_parse() function in self::parse(),
	 *                 call this method passing $parser as the first parameter
	 */
	private function handleNotationDeclaration(
		BaseParser $parser,
		string $notationName,
		string $base,
		string $systemId,
		string $publicId
	): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitNotationDeclaration($notationName, $base, $systemId, $publicId, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}

	/**
	 * handle unparsed entity declaration
	 *
	 * @param BaseParser $parser
	 * @param string     $entityName
	 * @param string     $base
	 * @param string     $systemId
	 * @param string     $publicId
	 * @param string     $notationName
	 *
	 * @psalm-suppress UnusedParam xml_parse() function in self::parse(),
	 *                 call this method passing $parser as the first parameter
	 */
	private function handleUnparsedEntitiyDeclaration(
		BaseParser $parser,
		string $entityName,
		string $base,
		string $systemId,
		string $publicId,
		string $notationName
	): void {
		/** @var XmlParserVisitorInterface $visitor */
		foreach ($this->visitors as $visitor) {
			$visitor->visitUnparsedEntityDeclaration($entityName, $base, $systemId, $publicId, $notationName, $this->getCurrentLineNumber(), $this->getCurrentColumnNumber());
		}
	}
}
