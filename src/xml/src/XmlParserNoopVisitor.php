<?php
namespace phootwork\xml;

class XmlParserNoopVisitor implements XmlParserVisitorInterface {

	public function visitElementEnd($name) {
	}

	public function visitProcessingInstruction($target, $data) {
	}

	public function visitNotationDeclaration($notationName, $base, $systemId, $publicId) {
	}

	public function visitUnparsedEntitiyDeclaration($entityName, $base, $systemId, $publicId, $notationName) {
	}

	public function visitElementStart($name, $attributes) {
	}

	public function visitCharacterData($data) {
	}

}
