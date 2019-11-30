<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\xml\tests;

use phootwork\collection\Stack;
use phootwork\file\Path;
use phootwork\lang\Text;
use phootwork\xml\exception\XmlException;
use phootwork\xml\XmlParser;
use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase {
	public function testConstants(): void {
		$this->assertSame(XML_OPTION_CASE_FOLDING, XmlParser::OPTION_CASE_FOLDING);
		$this->assertSame(XML_OPTION_SKIP_TAGSTART, XmlParser::OPTION_SKIP_TAGSTART);
		$this->assertSame(XML_OPTION_SKIP_WHITE, XmlParser::OPTION_SKIP_WHITE);
		$this->assertSame(XML_OPTION_TARGET_ENCODING, XmlParser::OPTION_TARGET_ENCODING);
	}

	public function testParser(): void {
		$parser = new XmlParser();

		// test options
		$caseFolding = $parser->getOption(XmlParser::OPTION_CASE_FOLDING);
		$parser->setOption(XmlParser::OPTION_CASE_FOLDING, 42);
		$this->assertEquals(42, $parser->getOption(XmlParser::OPTION_CASE_FOLDING));
		$parser->setOption(XmlParser::OPTION_CASE_FOLDING, $caseFolding);

		// test visitor
		$visitor = new StackParserVisitor();
		$parser->addVisitor($visitor);
		$parser->removeVisitor($visitor);
		$parser->parseFile(new Text(__DIR__ . '/fixtures/bookstore.xml'));
		$this->assertEquals(0, $visitor->getElementStack()->size());

		$parser = null;
	}

	public function testBookstore(): void {
		$stack = new Stack();
		$visitor = new StackParserVisitor();
		$parser = new XmlParser();
		$parser->addVisitor($visitor);
		$parser->parseFile(new Path(__DIR__ . '/fixtures/bookstore.xml'));

		$stack->push('database');
		$stack->push('entity');
		$stack->push('field', 'field', 'field', 'field', 'relation', 'relation');
		//$stack = $stack->map(function ($item) {
		//	return strtoupper($item);
		//});
		$this->assertEquals($stack, $visitor->getElementStack());

		$parser->__destruct();
	}

	public function testParseWrongContentThrowsException(): void {
		$this->expectException(XmlException::class);
		$this->expectExceptionMessage('Not well-formed (invalid token)');

		$parser = new XmlParser();
		$parser->parse('This is not an xml string');
	}
}
