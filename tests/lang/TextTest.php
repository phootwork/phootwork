<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\lang\tests;

use phootwork\lang\ArrayObject;
use phootwork\lang\tests\fixtures\Replace;
use phootwork\lang\tests\fixtures\Search;
use phootwork\lang\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase {
	public function testToText(): void {
		$str = new Text('bla');
		$this->assertEquals('bla', '' . $str);

		$str = Text::create('bla');
		$this->assertEquals('bla', '' . $str);
	}

	public function testLength(): void {
		$this->assertEquals(9, Text::create('let it go')->length());
		$this->assertEquals(6, Text::create('いちりんしゃ')->length());
		$this->assertEquals(17, Text::create('Ο συγγραφέας είπε')->length());
	}

	public function testStartsWith(): void {
		$str = new Text('let it go');

		$this->assertTrue($str->startsWith('let'));
		$this->assertTrue($str->startsWith(new Text('let')));
		$this->assertFalse($str->startsWith('go'));
		$this->assertFalse($str->startsWith(new Text('go')));
	}

	public function testStartsWithIgnoreCase(): void {
		$str = new Text('LeT it go');

		$this->assertFalse($str->startsWith('let'));
		$this->assertTrue($str->startsWithIgnoreCase('let'));
		$this->assertFalse($str->startsWith(new Text('let')));
		$this->assertTrue($str->startsWithIgnoreCase(new Text('let')));
		$this->assertFalse($str->startsWithIgnoreCase('go'));
		$this->assertFalse($str->startsWithIgnoreCase(new Text('go')));
	}

	public function testEndsWith(): void {
		$str = new Text('let it go');

		$this->assertTrue($str->endsWith('go'));
		$this->assertTrue($str->endsWith(new Text('go')));
		$this->assertFalse($str->endsWith('let'));
		$this->assertFalse($str->endsWith(new Text('let')));
	}

	public function testEndsWithIgnoreCase(): void {
		$str = new Text('LeT it GO');

		$this->assertFalse($str->endsWith('go'));
		$this->assertTrue($str->endsWithIgnoreCase('go'));
		$this->assertFalse($str->endsWith(new Text('go')));
		$this->assertTrue($str->endsWithIgnoreCase(new Text('go')));
		$this->assertFalse($str->endsWithIgnoreCase('let'));
		$this->assertFalse($str->endsWithIgnoreCase(new Text('let')));
	}

	public function testContains(): void {
		$str = new Text('let it go');

		$this->assertTrue($str->contains('it'));
		$this->assertTrue($str->contains(new Text('it')));
		$this->assertFalse($str->contains('Hulk'));
		$this->assertFalse($str->contains(new Text('Hulk')));
	}

	public function testEquals(): void {
		$str = new Text('let it go');

		$this->assertTrue($str->equals('let it go'));
		$this->assertTrue($str->equals(new Text('let it go')));
		$this->assertFalse($str->equals('Let It Go'));
	}

	public function testEqualsIgnoreCase(): void {
		$str = new Text('let it go');

		$this->assertTrue($str->equalsIgnoreCase('Let It Go'));
		$this->assertTrue($str->equalsIgnoreCase(new Text('Let It Go')));
	}

	public function testIsEmpty(): void {
		$str = new Text('let it go');
		$this->assertFalse($str->isEmpty());

		$emptyString = new Text('');
		$this->assertTrue($emptyString->isEmpty());
	}

	public function testMbStartsWith(): void {
		$str = new Text('Ο συγγραφέας είπε');

		$this->assertTrue($str->startsWith('Ο συγ'));
		$this->assertTrue($str->startsWith(new Text('Ο συγ')));
		$this->assertFalse($str->startsWith('ραφέ'));
		$this->assertFalse($str->startsWith(new Text('ραφέ')));
	}

	public function testMbEndsWith(): void {
		$str = new Text('Ο συγγραφέας είπε');

		$this->assertTrue($str->endsWith('είπε'));
		$this->assertTrue($str->endsWith(new Text('είπε')));
		$this->assertFalse($str->endsWith('ραφέ'));
		$this->assertFalse($str->endsWith(new Text('ραφέ')));
	}

	public function testMbContains(): void {
		$str = new Text('Ο συγγραφέας είπε');

		$this->assertTrue($str->contains('συγγραφέας'));
		$this->assertTrue($str->contains(new Text('συγγραφέας')));
		$this->assertFalse($str->contains('いちりんしゃ'));
		$this->assertFalse($str->contains(new Text('いちりんしゃ')));
	}

	public function testMbEquals(): void {
		$str = new Text('Ο συγγραφέας είπε');

		$this->assertTrue($str->equals('Ο συγγραφέας είπε'));
		$this->assertTrue($str->equals(new Text('Ο συγγραφέας είπε')));
		$this->assertFalse($str->equals('いちりんしゃ'));
		$this->assertFalse($str->equals(new Text('いちりんしゃ')));
	}

	public function testMbEqualsIgnoreCase(): void {
		$str = new Text('Ο συγγραφέας είπε');

		$this->assertTrue($str->equalsIgnoreCase('Ο συγγραφέας είπε'));
		$this->assertTrue($str->equalsIgnoreCase(new Text('Ο συγγραφέας είπε')));
	}

	public function testSlicing(): void {
		$str = new Text('let it go');

		$this->assertEquals('let', $str->slice(0, 3));
		$this->assertEquals('it', $str->slice(4, 2));
		$this->assertEquals(new Text(''), $str->slice(5, 0));
		$this->assertEquals('it go', $str->slice(4));
		$this->assertEquals('go', $str->slice(-2));

		$this->assertEquals('it go', $str->subString(4));
		$this->assertEquals('let', $str->subString(0, 3));
		$this->assertEquals('it', $str->subString(4, 6));
		$this->assertEquals('et it g', $str->subString(1, -1));
		$this->assertEquals('g', $str->subString(7, -1));

		// mb
		$str = new Text('Ο συγγραφέας είπε');

		$this->assertEquals('Ο σ', $str->slice(0, 3));
		$this->assertEquals('γγ', $str->slice(4, 2));
		$this->assertEquals(new Text(''), $str->slice(5, 0));
		$this->assertEquals('γγραφέας είπε', $str->slice(4));

		$this->assertEquals('γγραφέας είπε', $str->subString(4));
		$this->assertEquals('Ο σ', $str->subString(0, 3));
		$this->assertEquals('γγ', $str->subString(4, 6));
		$this->assertEquals(' συγγραφέας είπ', $str->subString(1, -1));
		$this->assertEquals('αφέας είπ', $str->subString(7, -1));
	}

	public function testMutators(): void {
		$str = new Text('it');

		$this->assertEquals('let it', $str->prepend('let '));
		$this->assertEquals('let it', $str->prepend(new Text('let ')));
		$this->assertEquals('it go', $str->append(' go'));
		$this->assertEquals('it go', $str->append(new Text(' go')));
		$this->assertEquals('iTTt', $str->insert('TT', 1));
		$this->assertEquals('TTit', $str->insert('TT', -1));
		$this->assertEquals('itTT', $str->insert('TT', 20));
	}

	public function testTrimming(): void {
		$str = new Text('  let it go  ');
		$this->assertEquals('let it go  ', $str->trimStart());
		$this->assertEquals('  let it go', $str->trimEnd());
		$this->assertEquals('let it go', $str->trim());

		$str = new Text('  fòôbàř  ');
		$this->assertEquals('fòôbàř  ', $str->trimStart());
		$this->assertEquals('  fòôbàř', $str->trimEnd());
		$this->assertEquals('fòôbàř', $str->trim());
	}

	public function testPadding(): void {
		$str = new Text('let it go');
		$this->assertEquals('-=let it go', $str->padStart(11, '-='));
		$this->assertEquals('-=let it go', $str->padStart(11, new Text('-=')));
		$this->assertEquals('let it go=-', $str->padEnd(11, '=-'));
		$this->assertEquals('let it go=-', $str->padEnd(11, new Text('=-')));
		$this->assertEquals('==let it go==', $str->pad(13, '=='));

		$str = new Text('fòôbàř');
		$this->assertEquals('-=fòôbàř', $str->padStart(8, '-='));
		$this->assertEquals('fòôbàř=-', $str->padEnd(8, '=-'));
		$this->assertEquals('==fòôbàř==', $str->pad(10, '=='));
		$this->assertSame($str, $str->pad(0));
	}

	public function testIndexSearch(): void {
		$str = new Text('let it go');
		$this->assertEquals(4, $str->indexOf('it'));
		$this->assertEquals(4, $str->indexOf(new Text('it')));

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals(2, $str->indexOf('Ö'));
		$this->assertEquals(2, $str->indexOf(new Text('Ö')));
	}

	public function testIndexSearchNullString(): void {
		$str = new Text('let it go');
		$this->assertEquals(0, $str->indexOf(''));
		$this->assertEquals(0, $str->indexOf(new Text('')));
	}

	public function testToLowerCase(): void {
		$str = new Text('LET IT GO');
		$lower = $str->toLowerCase();
		$this->assertInstanceOf(Text::class, $lower);
		$this->assertEquals('let it go', $lower->toString());
		$this->assertEquals('=let it go', '=' . $str->toLowerCase());

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals('äåöääåûüüû', $str->toLowerCase());
	}

	public function testToLowerCaseFirst(): void {
		$str = new Text('LET IT GO');
		$lower = $str->toLowerCaseFirst();
		$this->assertInstanceOf(Text::class, $lower);
		$this->assertEquals('lET IT GO', $lower->toString());
		$this->assertEquals('=lET IT GO', '=' . $str->toLowerCaseFirst());

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals('ÄåÖäÄåûüÜÛ', $str->toUpperCaseFirst());
	}

	public function testToUpperCase(): void {
		$str = new Text('let it go');
		$upper = $str->toUpperCase();
		$this->assertInstanceOf(Text::class, $upper);
		$this->assertEquals('LET IT GO', $upper->toString());
		$this->assertEquals('=LET IT GO', '=' . $str->toUpperCase());

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals('ÄÅÖÄÄÅÛÜÜÛ', $str->toUpperCase());
	}

	public function testToUpperCaseFirst(): void {
		$str = new Text('let it go');
		$upper = $str->toUpperCaseFirst();
		$this->assertInstanceOf(Text::class, $upper);
		$this->assertEquals('Let it go', $upper->toString());
		$this->assertEquals('=Let it go', '=' . $str->toUpperCaseFirst());

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals('ÄåÖäÄåûüÜÛ', $str->toUpperCaseFirst());
	}

	public function testToCapitalCase() {
		$str = new Text('let it go');
		$upper = $str->toCapitalCase();
		$this->assertInstanceOf(Text::class, $upper);
		$this->assertEquals('Let it go', $upper->toString());
		$this->assertEquals('=Let it go', '=' . $str->toCapitalCase());

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals('Äåöääåûüüû', $str->toCapitalCase());
	}

	public function testToCapitalCaseWords(): void {
		$str = new Text('let iT go');
		$upper = $str->toCapitalCaseWords();
		$this->assertInstanceOf(Text::class, $upper);
		$this->assertEquals('Let It Go', $upper->toString());
		$this->assertEquals('=Let It Go', '=' . $str->toCapitalCaseWords());

		// mb
		$str = new Text('äåÖäÄåûüÜÛ äåÖäÄåûüÜÛ');
		$this->assertEquals('Äåöääåûüüû Äåöääåûüüû', $str->toCapitalCaseWords());
	}

	public function testReplace(): void {
		$str = new Text('let it go');

		// string
		$repl = $str->replace(' it', '\'s');
		$this->assertEquals('let\'s go', $repl);
		$this->assertInstanceOf(Text::class, $repl);

		// Text objects
		$repl = $str->replace(new Text(' it'), new Text("'s"));
		$this->assertEquals('let\'s go', $repl);
		$this->assertInstanceOf(Text::class, $repl);

		// array
		$search = [' it', 'go'];
		$replace = ["'s", 'run'];

		$repl = $str->replace($search, $replace);
		$this->assertEquals('let\'s run', $repl);
		$this->assertInstanceOf(Text::class, $repl);

		// Arrayable
		$repl = $str->replace(new Search(), new Replace());
		$this->assertEquals('let\'s run', $repl);
		$this->assertInstanceOf(Text::class, $repl);

		// mb
		$str = new Text('äåÖäÄåûüÜÛ');
		$this->assertEquals('öåÖöÄåûüÜÛ', $str->replace('ä', 'ö'));
	}

	public function testSupplant(): void {
		$str = new Text('let it go');
		$search = [' it' => "'s", 'go' => 'run'];

		$repl = $str->supplant($search);
		$this->assertEquals('let\'s run', $repl);
		$this->assertInstanceOf(Text::class, $repl);
	}

	public function testSplice(): void {
		$str = new Text('Text to splice');

		$repl = $str->splice('', 4);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text', $repl);

		$repl = $str->splice('', -4);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text to sp', $repl);

		$repl = $str->splice('beautifull ', 5, 0);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text beautifull to splice', $repl);

		$repl = $str->splice(' you can', 4, 3);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text you can splice', $repl);

		$repl = $str->splice('replace', -6, 6);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text to replace', $repl);

		$repl = $str->splice('replace and ', 8, -6);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text to replace and splice', $repl);

		$repl = $str->splice(new Text(' you can'), 4, 3);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Text you can splice', $repl);

		// mb
		$str = new Text('Ο συγγραφέας είπε');

		$repl = $str->splice('', 2);
		$this->assertInstanceOf(Text::class, $repl);
		$this->assertEquals('Ο ', $repl);

		$this->assertEquals('Ο συγγραφέας', $str->splice('', -5));
		$this->assertEquals('Ο συγγραφέας', $str->splice('', -5));
		$this->assertEquals('Ο wurst συγγραφέας είπε', $str->splice('wurst ', 2, 0));
		$this->assertEquals('Ο συγγραφέας wurst', $str->splice('wurst', -4, 4));
	}

	public function testSpliceWrongOffsetThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Offset must be in range [-len, len]');

		$str = new Text('Text to splice');
		$str->splice('', 25);
	}

	public function testSpliceWrongNegativeOffsetThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Offset must be in range [-len, len]');

		$str = new Text('Text to splice');
		$str->splice('', -25);
	}

	public function testSpliceWrongLengthThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Length too large');

		$str = new Text('Text to splice');
		$str->splice('test', 4, 20);
	}

	public function testSpliceLengthSmallThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Length too small');

		$str = new Text('Text to splice');
		$str->splice('test', -4, -12);
	}

	public function testAt(): void {
		$str = new Text('Text to splice');
		$pos = $str->at(5);
		$this->assertSame('t', $pos);

		// mb
		$str = new Text('いちりんしゃ');
		$this->assertEquals('し', $str->at(4));
	}

	public function testChars(): void {
		$str = new Text('Text');
		$this->assertEquals(['T', 'e', 'x', 't'], $str->chars()->toArray());

		// mb
		$str = new Text('いちりんしゃ');
		$this->assertEquals(['い', 'ち', 'り', 'ん', 'し', 'ゃ'], $str->chars()->toArray());
	}

	public function testLastIndexOf(): void {
		$str = new Text('Text to test');
		$this->assertEquals(5, $str->lastIndexOf('to'));
		$this->assertEquals(12, $str->lastIndexOf(''));
		$this->assertEquals(3, $str->lastIndexOf('', 3));
	}

	public function testCountSubstring(): void {
		$str = new Text('Text to count total occurrencies');
		$this->assertEquals(2, $str->countSubstring('to'));
		$this->assertEquals(5, $str->countSubstring(new Text('t')));
	}

	public function testCountSubstringCaseInsensitive(): void {
		$str = new Text('Text text TEXT');
		$this->assertEquals(1, $str->countSubstring('te'));
		$this->assertEquals(3, $str->countSubstring('te', false));
	}

	public function testCountSubstringWithEmptyStringThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('$substring cannot be empty');

		$str = new Text('Text to count total occurrencies');
		$str->countSubstring('');
	}

	public function testCountWrongOffsetThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Offset must be in range [-len, len]');

		$str = new Text('Text to count');
		$str->splice('', 25);
	}

	public function testMatch(): void {
		$str = new Text('Text to search');
		$this->assertSame(true, $str->match('/to/'));
	}

	public function testToPlural(): void {
		$str = new Text('Book');
		$plural = $str->toPlural();
		$this->assertEquals('Books', $plural);
		$this->assertInstanceOf(Text::class, $plural);
	}

	public function testToSingular(): void {
		$str = new Text('teeth');
		$singular = $str->toSingular();
		$this->assertEquals('tooth', $singular);
		$this->assertInstanceOf(Text::class, $singular);
	}

	public function testToPluralManyWords(): void {
		$str = new Text('The book is on the table');
		$plural = $str->toPlural();
		$this->assertEquals('The book is on the tables', $plural);
	}

	public function testWrapWords(): void {
		$text = new Text(file_get_contents(__DIR__ . '/fixtures/lorem.txt'));
		$wrapped = $text->wrapWords();
		$this->assertInstanceOf(Text::class, $wrapped);

		$expected = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
est laborum.';
		$this->assertEquals($expected, $wrapped);
	}

	public function testWrapWordsCut(): void {
		$text = new Text(file_get_contents(__DIR__ . '/fixtures/lorem.txt'));
		$wrapped = $text->wrapWords(20, "\n", true);
		$expected = 'Lorem ipsum dolor
sit amet,
consectetur
adipiscing elit, sed
do eiusmod tempor
incididunt ut labore
et dolore magna
aliqua. Ut enim ad
minim veniam, quis
nostrud exercitation
ullamco laboris nisi
ut aliquip ex ea
commodo consequat.
Duis aute irure
dolor in
reprehenderit in
voluptate velit esse
cillum dolore eu
fugiat nulla
pariatur. Excepteur
sint occaecat
cupidatat non
proident, sunt in
culpa qui officia
deserunt mollit anim
id est laborum.';
		$this->assertEquals($expected, $wrapped);
	}

	public function testWrapWordsCustomBreak(): void {
		$text = new Text(file_get_contents(__DIR__ . '/fixtures/lorem.txt'));
		$wrapped = $text->wrapWords(75, '**', true);
		$expected = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod**tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim**veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea**commodo consequat. Duis aute irure dolor in reprehenderit in voluptate**velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat**cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id**est laborum.';
		$this->assertEquals($expected, $wrapped);
	}

	public function testRepeat(): void {
		$str = new Text('repeat');
		$rep = $str->repeat(4);
		$this->assertInstanceOf(Text::class, $rep);
		$this->assertEquals('repeatrepeatrepeatrepeat', $rep);
		$this->assertEquals('', $str->repeat(0));
	}

	public function testRepeatNegativeTimesThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Number of repetitions can not be negative');
		$str = new Text('repeat');
		$str->repeat(-2);
	}

	public function testReverse(): void {
		$str = new Text('Hello world!');
		$rev = $str->reverse();
		$this->assertInstanceOf(Text::class, $rev);
		$this->assertEquals('!dlrow olleH', $rev);
	}

	public function testTruncate(): void {
		$str = new Text('Hello World!');
		$truncate = $str->truncate(8, '...');
		$this->assertEquals('Hello...', $truncate);
		$this->assertEquals(8, $truncate->length());
		$this->assertEquals('Hello World!', $str->truncate(20));

		$str = Text::create('いちりんしゃ');
		$this->assertEquals('いち', $str->truncate(2));
		$this->assertEquals('いち...', $str->truncate(5, '...'));
	}

	public function testChunk(): void {
		$str = new Text('Let it go');
		$splitted = $str->chunk();
		$this->assertInstanceOf(ArrayObject::class, $splitted);
		$this->assertEquals(['L', 'e', 't', ' ', 'i', 't', ' ', 'g', 'o'], $splitted->toArray());

		$splitted = $str->chunk(3);
		$this->assertEquals(['Let', ' it', ' go'], $splitted->toArray());

		$splitted = $str->chunk(30);
		$this->assertEquals(['Let it go'], $splitted->toArray());
	}

	public function testChunkNegativeLengthThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The chunk length has to be positive');

		$str = new Text('Let it go');
		$str->chunk(-1);
	}

	public function testChunkZeroLengthThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The chunk length has to be positive');
		$str = new Text('Let it go');
		$str->chunk(0);
	}

	public function testIsAlphanumeric(): void {
		$this->assertTrue((new Text('AbCd1zyZ9'))->isAlphanumeric());
		$this->assertFalse((new Text('AbC?d1z#yZ$9'))->isAlphanumeric());
		$this->assertFalse((new Text(''))->isAlphanumeric(), 'Null string is not alphanumeric');
	}

	public function testIsAlphabetic(): void {
		$this->assertTrue((new Text('AbCdzyZ'))->isAlphabetic());
		$this->assertFalse((new Text('AbC?d1z#yZ$9'))->isAlphabetic());
		$this->assertFalse((new Text('123456789'))->isAlphabetic());
		$this->assertFalse((new Text(''))->isAlphabetic(), 'Null string is not alphabetic');
	}

	public function testIsNumeric(): void {
		$this->assertTrue((new Text('125874698'))->isNumeric());
		$this->assertFalse((new Text('AbC?d1z#yZ$9'))->isNumeric());
		$this->assertFalse((new Text('qwerty'))->isNumeric());
		$this->assertFalse((new Text(''))->isNumeric(), 'Null string is not numeric');
	}

	public function testIsPunctuation(): void {
		$this->assertTrue((new Text('#@[{}?^&%.;'))->isPunctuation());
		$this->assertFalse((new Text('AbC?d1z#yZ$9'))->isPunctuation());
		$this->assertFalse((new Text(''))->isPunctuation(), 'Null string is not punctuation');
	}

	public function testIsSpace(): void {
		$this->assertTrue((new Text('  '))->isSpace());
		$this->assertFalse((new Text(' 9 '))->isSpace());
		$this->assertFalse((new Text(''))->isSpace(), 'Null string is not space');
	}

	public function testIsLowercase(): void {
		$this->assertTrue((new Text('lowercase'))->isLowerCase());
		$this->assertFalse((new Text('The show must go on'))->isLowerCase());
		$this->assertFalse((new Text(''))->isLowerCase(), 'Null string is not lowercase');

		//@todo is it a desirable behavior? Spaces are considered non-lowercase characters
		$this->assertFalse((new Text('lowercase string'))->isLowerCase());
	}

	public function testIsUpperCase(): void {
		$this->assertTrue((new Text('UPPERCASE'))->isUpperCase());
		$this->assertFalse((new Text('nONUPPERCASE'))->isUpperCase());
		$this->assertFalse((new Text(''))->isUpperCase(), 'Null string is not uppercase');

		//@todo is it a desirable behavior? Spaces are considered non-uppercase characters
		$this->assertFalse((new Text('UPPERCASE STRING'))->isUpperCase());
	}

	public function testIsSingular(): void {
		$this->assertTrue((new Text('chair'))->isSingular());
		$this->assertFalse((new Text('tables'))->isSingular());
	}

	public function testIsPlural(): void {
		$this->assertFalse((new Text('chair'))->isPlural());
		$this->assertTrue((new Text('tables'))->isPlural());
	}

	public function testToCamelCase(): void {
		$this->assertEquals('camelCaseString', Text::create('camelCaseString')->toCamelCase());
		$this->assertEquals('snakeCaseString', (new Text('snake_case_string'))->toCamelCase());
		$this->assertEquals('kebabCaseString', (new Text('kebab-case-string'))->toCamelCase());
		$this->assertEquals('', (new Text(''))->toCamelCase());
		$this->assertEquals('stringWith3Numbers2', (new Text('string_with_3_numbers2'))->toCamelCase());
		$this->assertEquals('stringWith3Numbers2', (new Text('string-with-3-numbers2'))->toCamelCase());
		$this->assertEquals('specialStart', (new Text('--special start'))->toCamelCase());
		$this->assertEquals('specialStart', (new Text('_special-start'))->toCamelCase());
		$this->assertEquals('stringWithSpaces', Text::create('string with spaces')->toCamelCase());
		$this->assertEquals('stringWithMultipleSpaces', Text::create('String    with multiple      spaces')->toCamelCase());
	}

	public function testToStudlyCase(): void {
		$this->assertEquals('StudlyCaseString', Text::create('StudlyCaseString')->toStudlyCase());
		$this->assertEquals('SnakeCaseString', (new Text('snake_case_string'))->toStudlyCase());
		$this->assertEquals('KebabCaseString', (new Text('kebab-case-string'))->toStudlyCase());
		$this->assertEquals('', (new Text(''))->toStudlyCase());
		$this->assertEquals('StringWith3Numbers2', (new Text('string_with_3_numbers2'))->toStudlyCase());
		$this->assertEquals('StringWith3Numbers2', (new Text('string-with-3-numbers2'))->toStudlyCase());
		$this->assertEquals('SpecialStart', (new Text('--special-start'))->toStudlyCase());
		$this->assertEquals('SpecialStart', (new Text('_special start'))->toStudlyCase());
		$this->assertEquals('StringWithSpaces', Text::create('string with spaces')->toStudlyCase());
		$this->assertEquals('StringWithMultipleSpaces', Text::create('string    with multiple      spaces')->toStudlyCase());
	}

	public function testToSnakeCase(): void {
		$this->assertEquals('snake_case_string', (new Text('snake_case_string'))->toSnakeCase());
		$this->assertEquals('camel_case_string', (new Text('camelCaseString'))->toSnakeCase());
		$this->assertEquals('studly_case_string', (new Text('StudlyCaseString'))->toSnakeCase());
		$this->assertEquals('kebab_case_string', (new Text('kebab-case-string'))->toSnakeCase());
		$this->assertEquals('', (new Text(''))->toSnakeCase());
		$this->assertEquals('string_with3_numbers2', (new Text('StringWith3Numbers2'))->toSnakeCase());
		$this->assertEquals('string_with_3_numbers2', (new Text('string-with-3-numbers2'))->toSnakeCase());
		$this->assertEquals('special_start', (new Text('--special start'))->toSnakeCase());
		$this->assertEquals('special_start', (new Text('_special-start'))->toSnakeCase());
		$this->assertEquals('string_with_spaces', Text::create('string with spaces')->toSnakeCase());
		$this->assertEquals('string_with_multiple_spaces', Text::create('string    with Multiple      Spaces')->toSnakeCase());
	}

	public function testToKebabCase(): void {
		$this->assertEquals('kebab-case-string', (new Text('kebab-case-string'))->toKebabCase());
		$this->assertEquals('camel-case-string', (new Text('camelCaseString'))->toKebabCase());
		$this->assertEquals('studly-case-string', (new Text('StudlyCaseString'))->toKebabCase());
		$this->assertEquals('snake-case-string', (new Text('snake_case_string'))->toKebabCase());
		$this->assertEquals('', (new Text(''))->toKebabCase());
		$this->assertEquals('string-with3-numbers2', (new Text('StringWith3Numbers2'))->toKebabCase());
		$this->assertEquals('string-with-3-numbers2', (new Text('string_with_3_numbers2'))->toKebabCase());
		$this->assertEquals('special-start', (new Text('--special start'))->toKebabCase());
		$this->assertEquals('special-start', (new Text('_special-start'))->toKebabCase());
		$this->assertEquals('string-with-spaces', Text::create('string with spaces')->toKebabCase());
		$this->assertEquals('string-with-multiple-spaces', Text::create('string    With multiple      Spaces')->toKebabCase());
	}

	public function testEnsureStart(): void {
		$text = new Text('phootwork');
		$this->assertEquals('phootwork', $text->ensureStart('phoot'));
		$text = new Text('work');
		$this->assertEquals('phootwork', $text->ensureStart('phoot'));
	}

	public function testEnsureEnd(): void {
		$text = new Text('phootwork');
		$this->assertEquals('phootwork', $text->ensureEnd('work'));
		$text = new Text('phoot');
		$this->assertEquals('phootwork', $text->ensureEnd('work'));
	}

	public function testJoin(): void {
		$array = ['Phootwork', 'is', 'a', 'collection', 'of', 'awesome', 'libraries'];
		$expected = 'Phootwork is a collection of awesome libraries';
		$this->assertEquals($expected, Text::join($array, ' '));
	}

	public function testToSpaces(): void {
		$text = new Text("\n\tbeautiful\n\t\tstring\n");
		$expected = '
    beautiful
        string
';
		$this->assertEquals($expected, $text->toSpaces());
	}

	public function testToTabs(): void {
		$text = new Text("hello\n      world");
		$this->assertEquals("hello\n\t  world", $text->toTabs());
	}

	public function testConstructPassingArrayThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The constructor parameter cannot be an array');

		$text = new Text([1, 'a', 2]);
	}

	public function testConstructPassingNonStringableObjectThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Passed object must implement  `__toString` method');

		$text = new Text(new \stdClass());
	}

	public function testEncoding(): void {
		$text = new Text('Come on');
		$this->assertEquals('UTF-8', $text->getEncoding(), 'Default encoding is UTF-8');

		$text = new Text('Come on', 'ISO-8859-1');
		$this->assertEquals('ISO-8859-1', $text->getEncoding());
	}

	public function testSplitEmptyDelimiterThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The delimiter can\'t be an empty string');

		$text = new Text('Let it go');
		$text->split('');
	}
}
