<?php
namespace phootwork\xml\tests;

use phootwork\xml\XmlParser;
use phootwork\collection\Stack;

class XmlParserTest extends \PHPUnit_Framework_TestCase {
	
	public function testConstants() {
		$this->assertSame(XML_OPTION_CASE_FOLDING, XmlParser::OPTION_CASE_FOLDING);
		$this->assertSame(XML_OPTION_SKIP_TAGSTART, XmlParser::OPTION_SKIP_TAGSTART);
		$this->assertSame(XML_OPTION_SKIP_WHITE, XmlParser::OPTION_SKIP_WHITE);
		$this->assertSame(XML_OPTION_TARGET_ENCODING, XmlParser::OPTION_TARGET_ENCODING);
	}
	
	public function testParser() {
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
		$parser->parseFile(__DIR__ . '/fixtures/bookstore.xml');
		$this->assertEquals(0, $visitor->getElementStack()->size());
		
		$parser = null;
	}

	public function testBookstore() {
		$stack = new Stack();
		$visitor = new StackParserVisitor();
		$parser = new XmlParser();
		$parser->addVisitor($visitor);
		$parser->parseFile(__DIR__ . '/fixtures/bookstore.xml');

		$stack->push('database');
		$stack->push('entity');
		$stack->pushAll(['field', 'field', 'field', 'field', 'relation', 'relation']);
		$stack = $stack->map(function ($item) {
			return strtoupper($item);
		});
		$this->assertEquals($stack, $visitor->getElementStack());
		
	}
}
