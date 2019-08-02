<?php
namespace phootwork\xml;

class XmlParserNoopVisitor implements XmlParserVisitorInterface {

	public function visitElementEnd($name, $line, $column) {
	}

	public function visitProcessingInstruction($target, $data, $line, $column) {
	}

	public function visitNotationDeclaration($notationName, $base, $systemId, $publicId, $line, $column) {
	}

	public function visitUnparsedEntitiyDeclaration($entityName, $base, $systemId, $publicId, $notationName, $line, $column) {
	}

	public function visitElementStart($name, $attributes, $line, $column) {
	}

	public function visitCharacterData($data, $line, $column) {
	}

}
