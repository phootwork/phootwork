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
use phootwork\lang\ComparableComparator;
use phootwork\lang\StringComparator;
use phootwork\lang\tests\fixtures\Item;
use phootwork\lang\Text;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase {
	public function testArray(): void {
		$base = ['a' => 'b', 'c' => 'd'];
		$arr = new ArrayObject($base);

		$this->assertEquals(new ArrayObject(['b', 'd']), $arr->values());
		$this->assertEquals(new ArrayObject(['a', 'c']), $arr->keys());
		$this->assertEquals($base, $arr->toArray());

		$new = [];
		foreach ($arr as $k => $v) {
			$new[$k] = $v;
		}
		$this->assertEquals($new, $arr->toArray());

		$this->assertEquals(new ArrayObject(['b' => 'a', 'd' => 'c']), $arr->flip());

		$arr = new ArrayObject(['these', 'are', 'my', 'items']);
		$this->assertEquals(new Text('these are my items'), $arr->join(' '));
		$arr->clear();
		$this->assertEquals(0, $arr->count());

		$arr = new ArrayObject();
		$this->assertTrue($arr->isEmpty());
	}

	public function testCount(): void {
		$arr = new ArrayObject(['these', 'are', 'my', 'items']);

		$this->assertEquals(4, $arr->count());
		$this->assertEquals(4, count($arr));

		$arr->merge(['a', 'b']);

		$this->assertEquals(6, $arr->count());
	}

	public function testArrayAccess(): void {
		$arr = new ArrayObject(['a' => 'b', 'c' => 'd']);

		$this->assertEquals('b', $arr['a']);
		$this->assertTrue(isset($arr['c']));
		$this->assertFalse(isset($arr['x']));
		unset($arr['c']);
		$this->assertFalse(isset($arr['c']));
		$arr['a'] = 'x';
		$this->assertEquals('x', $arr['a']);
	}

	public function testSerialization(): void {
		$arr = new ArrayObject(['these', 'are', 'my', 'items']);
		$serialized = $arr->serialize();

		$brr = new ArrayObject();
		$brr->unserialize($serialized);

		$this->assertEquals($arr, $brr);
	}

	public function testExternalSerialization(): void {
		$arr = new ArrayObject(['these', 'are', 'my', 'items']);
		$serialized = serialize($arr);

		$this->assertEquals($arr, unserialize($serialized));
	}

	public function testReduce(): void {
		$list = new ArrayObject(range(1, 10));
		$sum = $list->reduce(function ($a, $b) {
			return $a + $b;
		});

		$this->assertEquals(55, $sum);
	}

	public function testFilter(): void {
		$arr = new ArrayObject(['a' => 'a', 'b' => 'b', 'c' => 'c']);
		$arr = $arr->filter(function ($item) {
			return $item != 'b';
		});

		$this->assertSame(['a' => 'a', 'c' => 'c'], $arr->toArray());
	}

	public function testMap(): void {
		$arr = new ArrayObject(['a' => 'a', 'b' => 'b', 'c' => 'c']);
		$arr = $arr->map(function ($item) {
			return $item . 'val';
		});

		$this->assertSame(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr->toArray());
	}

	public function testSort(): void {
		$unsorted = [5, 2, 8, 3, 9, 4, 6, 1, 7, 10];
		$list = new ArrayObject($unsorted);

		$this->assertEquals(range(1, 10), $list->sort()->toArray());
		$this->assertEquals(range(10, 1), $list->reverse()->toArray());

		$list = new ArrayObject($unsorted);
		$cmp = function ($a, $b) {
			if ($a == $b) {
				return 0;
			}

			return ($a < $b) ? -1 : 1;
		};
		$this->assertEquals(range(1, 10), $list->sort($cmp)->toArray());

		$items = ['x', 'c', 'a', 't', 'm'];
		$list = new ArrayObject();
		foreach ($items as $item) {
			$list->append(new Item($item));
		}

		$list->sort(new ComparableComparator());
		$this->assertEquals(['a', 'c', 'm', 't', 'x'], $list->map(function (Item $item) {
			return $item->getContent();
		})->toArray());
	}

	public function testSortNotComparableThrowsException(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('ComparableComparator can compare only objects implementing phootwork\lang\Comparable interface');

		$items = [(object) 'x', (object) 'c', (object) 'a'];
		$list = new ArrayObject();
		foreach ($items as $item) {
			$list->append($item);
		}

		$list->sort(new ComparableComparator());
	}

	public function testSortAssoc(): void {
		$arr = new ArrayObject(['b' => 'bval', 'a' => 'aval', 'c' => 'cval']);
		$arr->sortAssoc();
		$this->assertEquals(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr->toArray());

		$arr = new ArrayObject(['b' => 'bval', 'a' => 'aval', 'c' => 'cval']);
		$arr->sortAssoc(function ($a, $b) {
			if ($a == $b) {
				return 0;
			}

			return ($a < $b) ? -1 : 1;
		});
		$this->assertEquals(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr->toArray());

		$arr = new ArrayObject(['b' => new Item('bval'), 'a' => new Item('aval'), 'c' => new Item('cval')]);
		$arr->sortAssoc(new ComparableComparator());
		$this->assertEquals(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr
				->map(function (Item $elem) {
					return $elem->getContent();
				})
				->toArray());
	}

	public function testSortKeys(): void {
		$arr = new ArrayObject(['b' => 'bval', 'a' => 'aval', 'c' => 'cval']);
		$arr->sortKeys();
		$this->assertEquals(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr->toArray());

		$arr = new ArrayObject(['b' => 'bval', 'a' => 'aval', 'c' => 'cval']);
		$arr->sortKeys(function ($a, $b) {
			if ($a == $b) {
				return 0;
			}

			return ($a < $b) ? -1 : 1;
		});
		$this->assertEquals(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr->toArray());

		$arr = new ArrayObject(['b' => 'bval', 'a' => 'aval', 'c' => 'cval']);
		$arr->sortKeys(new StringComparator());
		$this->assertEquals(['a' => 'aval', 'b' => 'bval', 'c' => 'cval'], $arr->toArray());
	}

	public function testMutators(): void {
		$base = ['b', 'c', 'd'];
		$arr = new ArrayObject($base);
		$arr->append('e', 'f');

		$this->assertEquals(['b', 'c', 'd', 'e', 'f'], $arr->toArray());
		$this->assertTrue(array_is_list($arr->toArray()), 'Appending items preserve indexes order');
		$this->assertEquals('f', $arr->pop());
		$this->assertEquals('e', $arr->pop());
		$this->assertTrue(array_is_list($arr->toArray()), 'Popping items preserve indexes order');
		$arr->prepend('a');
		$this->assertEquals(['a', 'b', 'c', 'd'], $arr->toArray());
		$this->assertTrue(array_is_list($arr->toArray()), 'Prepending items preserve indexes order');
		$this->assertEquals('a', $arr->shift());
		$this->assertEquals($base, $arr->toArray());
		$this->assertTrue(array_is_list($arr->toArray()), 'Shifting items preserve indexes order');
		$arr->prepend('a');
		$this->assertEquals(['a', 'b', 'c', 'd'], $arr->toArray());
	}

	public function testEach(): void {
		$result = [];
		$list = new ArrayObject(range(1, 10));
		$list->each(function ($value) use (&$result) {
			$result[] = $value;
		});
		$this->assertEquals($list->toArray(), $result);
	}

	public function testIndex(): void {
		$item1 = 'item 1';
		$item2 = 'item 2';
		$item3 = 'item 3';
		$items = [$item1, $item2];

		$list = new ArrayObject($items);

		$index1 = $list->indexOf($item1);
		$this->assertEquals(0, $index1);
		$this->assertEquals(1, $list->indexOf($item2));
		$this->assertNull($list->indexOf($item3));

		$list->remove($item1, $item2);
		$list->add(...$items);

		$this->assertEquals(2, $list->count());
		$this->assertEquals($index1, $list->indexOf($item1));

		$list->insert($item3, 1);
		$this->assertEquals($item3, $list->get(1));
		$this->assertEquals($item2, $list->get(2));
	}

	public function testIndexAssociative(): void {
		$items = ['item1' => 'item 1', 'item2' => 'item 2'];
		$item3 = 'item 3';

		$list = new ArrayObject($items);

		$this->assertEquals('item1', $list->indexOf('item 1'));
		$this->assertEquals('item2', $list->indexOf('item 2'));
		$this->assertNull($list->indexOf($item3));
		$this->assertEquals(2, $list->count());

		$list->insert($item3, 'item3');
		$this->assertEquals($item3, $list->get('item3'));
		$this->assertEquals('item 2', $list->get('item2'));
	}

	public function testContains(): void {
		$item1 = 'item 1';
		$item2 = 'item 2';
		$item3 = 'item 3';
		$items = [$item1, $item2];

		$list = new ArrayObject($items);

		$this->assertTrue($list->contains($item2));
		$this->assertFalse($list->contains($item3));
	}

	public function testFind(): void {
		$list = new ArrayObject(range(1, 10));
		$list = $list->map(function (int $item) {
			return new Item((string) $item);
		});

		$search = function (Item $i, $query) {
			return $i->getContent() == $query;
		};

		/** @var Item $item */
		$item = $list->find(4, $search);
		$this->assertTrue($item instanceof Item);
		$this->assertEquals(4, $item->getContent());
		$this->assertEquals(3, $list->findIndex(4, $search));
		$this->assertNull($list->find(20, $search));

		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$fruits = $fruits->map(function ($item) {
			return new Item($item);
		});
		$this->assertEquals(1, $fruits->findIndex(function (Item $elem) {
			return $elem->getContent() == 'banana';
		}));
		$this->assertEquals(3, $fruits->findLastIndex(function (Item $elem) {
			return $elem->getContent() == 'banana';
		}));
		$this->assertEquals(3, $fruits->findLastIndex('banana', function (Item $elem, $query) {
			return $elem->getContent() == $query;
		}));
		$this->assertNull($fruits->findLast('mango', function (Item $elem, $query) {
			return $elem->getContent() == $query;
		}));

		$apples = $fruits->findAll('apple', function (Item $elem, $query) {
			return $elem->getContent() == $query;
		});
		$this->assertEquals(1, $apples->count());

		$bananas = $fruits->findAll(function (Item $elem) {
			return $elem->getContent() == 'banana';
		});
		$this->assertEquals(2, $bananas->count());
	}

	public function testSearch(): void {
		$list = new ArrayObject(range(1, 10));
		$search = function ($elem, $query) {
			return $elem == $query;
		};

		$this->assertTrue($list->search(4, $search));
		$this->assertFalse($list->search(20, $search));

		$this->assertTrue($list->search(function ($elem) {
			return $elem == 4;
		}));
		$this->assertFalse($list->search(function ($elem) {
			return $elem == 20;
		}));
	}

	public function testIndexOf(): void {
		$animals = new ArrayObject([
			'quadrupeds' => [
				'canids' => ['dog', 'wolfe'],
				'felines' => ['cat', 'panther']
			],
			'bipedal' => 'chicken',
			'cetaceans' => ['dolphin', 'whale']
		]);

		$this->assertEquals('cetaceans', $animals->indexOf(['dolphin', 'whale']));
		$this->assertEquals('bipedal', $animals->indexOf('chicken'));
		$this->assertNull($animals->indexOf('canids'), 'Can\'t work on deep levels of multidimensional arrays');
	}

	public function testFindIndexAssociative(): void {
		$animals = new ArrayObject([
			'quadrupeds' => [
				'canids' => ['dog', 'wolfe'],
				'felines' => ['cat', 'panther']
			],
			'bipedal' => ['human', 'chicken'],
			'cetaceans' => ['dolphin', 'whale']
		]);
		$index = $animals->findIndex(['human', 'chicken'], function (array $element, array $query) {
			return $element === $query;
		});

		$this->assertEquals('bipedal', $index);
	}

	public function testFindAssociative(): void {
		$animals = new ArrayObject([
			'quadrupeds' => [
				'canids' => ['dog', 'wolfe'],
				'felines' => ['cat', 'panther']
			],
			'bipedal' => ['human', 'chicken'],
			'cetaceans' => ['dolphin', 'whale']
		]);
		$arr = $animals->find('whale', function (array $element, string $query) {
			return in_array($query, $element) ? $element : false;
		});

		$this->assertEquals(['dolphin', 'whale'], $arr);
	}

	public function testSome(): void {
		$list = new ArrayObject(range(1, 10));

		$this->assertTrue($list->some(function ($item) {
			return $item % 2 === 0;
		}));

		$this->assertFalse($list->some(function ($item) {
			return $item > 10;
		}));

		$list = new ArrayObject();
		$this->assertFalse($list->some(function () {
			return true;
		}));
	}

	public function testEvery(): void {
		$list = new ArrayObject(range(1, 10));

		$this->assertTrue($list->every(function ($item) {
			return $item <= 10;
		}));

		$this->assertFalse($list->every(function ($item) {
			return $item > 10;
		}));

		$list = new ArrayObject();
		$this->assertTrue($list->every(function () {
			return true;
		}));
	}

	public function testSlice(): void {
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);

		$this->assertEquals(['banana', 'pine'], $fruits->slice(1, 2)->toArray());
	}

	public function testSplice(): void {
		// delete
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$this->assertEquals(['apple', 'banana'], $fruits->splice(2)->toArray());

		// cut
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$this->assertEquals(['apple', 'ananas'], $fruits->splice(1, -1)->toArray());

		// replace to end
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$this->assertEquals(['apple', 'orange'], $fruits->splice(1, $fruits->count(), ['orange'])->toArray());

		// replace
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$this->assertEquals(['apple', 'strawberry', 'blackberry', 'banana', 'ananas'], $fruits->splice(1, 2, ['strawberry', 'blackberry'])->toArray());

		// insert array
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$this->assertEquals(['apple', 'banana', 'pine', 'orange', 'strawberry', 'banana', 'ananas'], $fruits->splice(3, 0, ['orange', 'strawberry'])->toArray());

		// insert string
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$this->assertEquals(['apple', 'banana', 'pine', 'orange', 'banana', 'ananas'], $fruits->splice(3, 0, ['orange'])->toArray());
	}

	public function testClone(): void {
		$fruits = new ArrayObject(['apple', 'banana', 'pine', 'banana', 'ananas']);
		$clonedFruits = clone $fruits;
		$this->assertNotSame($fruits, $clonedFruits);
		$this->assertSame($fruits->toArray(), $clonedFruits->toArray());
	}

	public function testAppend(): void {
		$fruits = new ArrayObject(['apple', 'banana']);
		$fruits->append('pine', 'ananas');
		$this->assertEquals(['apple', 'banana', 'pine', 'ananas'], $fruits->toArray());
		$fruits->append(['peach', 'pear']);
		$this->assertEquals(['apple', 'banana', 'pine', 'ananas', ['peach', 'pear']], $fruits->toArray());
		$fruits->append($obj = new ArrayObject(['watermelon']));
		$this->assertEquals(['apple', 'banana', 'pine', 'ananas', ['peach', 'pear'], $obj], $fruits->toArray());
	}

	public function testGetWithNotExistentIndex(): void {
		$fruits = new ArrayObject(['apple', 'banana']);
		$element = $fruits->get(4);
		$this->assertNull($element);
	}

	public function testMerge(): void {
		$fruits = new ArrayObject(['apple', 'apricot', 'peach', 'banana']);
		$fruits->merge(['ananas', 'watermelon']);
		$expected = ['apple', 'apricot', 'peach', 'banana', 'ananas', 'watermelon'];
		$this->assertEquals($expected, $fruits->toArray());
	}

	public function testMergeMultidimensional(): void {
		$cartoons = [
			'Marvel' => [
				'avengers' => [
					'Iron Man',
					'Hulk',
					'Captain America'
				]
			],
			'Dc' => [
				'Superman',
				'Bat Man'
			]
		];

		$goNagai = [
			'Go Nagai' => [
				'Mazinger-Z',
				'Great Mazinger',
				'Goldrake'
			]
		];

		$addMarvel = [
			'Marvel' => [
				'X-Men' => [
					'Wolverine',
					'Professor X',
					'Magneto'
				]
			]
		];

		$expected = array_merge($cartoons, $goNagai, $addMarvel);
		$obj = new ArrayObject($cartoons);

		$this->assertEquals($expected, $obj->merge($addMarvel, $goNagai)->toArray());
	}

	public function testMergeRecursive(): void {
		$animals = [
			'quadrupeds' => [
				'canids' => ['dog', 'wolfe'],
				'felines' => ['cat', 'panther']
				],
			'bipedal' => ['human', 'chicken']
		];

		$toMerge1 = ['quadrupeds' => ['felines' => ['lion', 'tiger']]];
		$toMerge2 = ['cetaceans' => ['dolphin', 'whale']];

		$expected = array_merge_recursive($animals, $toMerge1, $toMerge2);
		$obj = new ArrayObject($animals);

		$this->assertEquals($expected, $obj->mergeRecursive($toMerge1, $toMerge2)->toArray());
	}

	public function testInsert(): void {
		$fruits = new ArrayObject(['apple', 'banana']);
		$fruits->insert('peach', 1);

		$this->assertEquals(3, $fruits->size());
		$this->assertEquals('apple', $fruits->get(0));
		$this->assertEquals('peach', $fruits->get(1));
		$this->assertEquals('banana', $fruits->get(2));
		$this->assertTrue(array_is_list($fruits->toArray()), 'Inserting items preserve indexes order');

		$fruits->insert('pear', null);
		$this->assertEquals(4, $fruits->size());
		$this->assertEquals('apple', $fruits->get(0));
		$this->assertEquals('peach', $fruits->get(1));
		$this->assertEquals('banana', $fruits->get(2));
		$this->assertEquals('pear', $fruits->get(3));
	}

	public function testInsertAssociativeArray(): void {
		$alimony = new ArrayObject([
			'fruits' => ['apple', 'banana'],
			'vegetables' => ['spinach', 'artichokes']
		]);

		$alimony->insert(['chickpeas', 'beans'], 'legumes');

		$this->assertEquals(3, $alimony->size());
		$this->assertEquals(['apple', 'banana'], $alimony->get('fruits'));
		$this->assertEquals(['chickpeas', 'beans'], $alimony->get('legumes'));

		$alimony->insert(['potatoes', 'carrots'], null); //adds the element at the end of the array
		$this->assertEquals(4, $alimony->size());
		$this->assertEquals(['potatoes', 'carrots'], $alimony->get(3));
	}

	public function testAddNotOverwriteElements(): void {
		$fruits = new ArrayObject(['apple', 'banana', 'peach']);
		$this->assertEquals(3, $fruits->size());

		$fruits->remove('banana');
		$this->assertEquals(2, $fruits->size());
		$this->assertEquals(['apple', 'peach'], $fruits->toArray());
		$fruits->add('apricot');

		$this->assertEquals(3, $fruits->size());
		$this->assertEquals(['apple', 'peach', 'apricot'], $fruits->toArray());
		$this->assertTrue(array_is_list($fruits->toArray()));
	}

	public function testRemoveAssocArray(): void {
		$animals = new ArrayObject([
			'canid' => 'wolfe',
			'feline' => 'lion',
			'reptile' => 'mamba'
		]);
		$this->assertEquals(3, $animals->size());

		$animals->remove('lion');
		$this->assertEquals(2, $animals->size());
		$this->assertEquals(['canid' => 'wolfe', 'reptile' => 'mamba'], $animals->toArray());
	}

	public function testInsertArrayableObject(): void {
		$cartoons = new ArrayObject();
		$goNagai = new ArrayObject(['Mazinger Z', 'Great Mazinger']);
		$marvel = new ArrayObject(['X-Men', 'Spiderman', 'Devil']);
		$dc = new ArrayObject(['Superman', 'Wonder Woman', 'Bat Man']);
		$cartoons->add($goNagai, $marvel);

		$this->assertEquals(2, $cartoons->size());
		$this->assertEquals([$goNagai, $marvel], $cartoons->toArray());

		$cartoons->insert($dc, 1);

		$this->assertEquals(3, $cartoons->size());
		$this->assertEquals([$goNagai, $dc, $marvel], $cartoons->toArray());
	}

	public function testInsertNull(): void {
		$goNagai = new ArrayObject(['Mazinger Z', 'Great Mazinger']);
		$goNagai->insert(null, 0);

		$this->assertEquals(3, $goNagai->count());
		$this->assertEquals([null, 'Mazinger Z', 'Great Mazinger'], $goNagai->toArray());
	}

	public function testJoinWherWrongTypeThrowsException(): void {
		$this->expectException(\TypeError::class);
		$this->expectExceptionMessage('Can join elements only if scalar, null or \\Stringable');

		$obj = new ArrayObject([new \StdClass, 'string', true]);
		$obj->join(',');
	}
}
