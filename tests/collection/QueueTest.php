<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\collection\tests;

use phootwork\collection\Queue;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase {
    public function testAddRemove(): void {
        $item1 = 'item 1';
        $item2 = 'item 2';
        $item3 = 'item 3';
        $items = [$item1, $item2];

        $queue = new Queue();
        $queue->enqueue($item1);

        $this->assertEquals(1, $queue->size());
        $this->assertEquals($item1, $queue->poll());

        $this->assertEquals(0, $queue->size());

        $queue->enqueueAll($items);

        $this->assertEquals(2, $queue->size());

        $queue->enqueue($item3);

        $this->assertEquals(3, $queue->size());
    }

    public function testToArray(): void {
        $queue = new Queue(['item 1', 'item 2', 'item 3']);
        $this->assertSame('item 1', $queue->peek());
        $this->assertEquals($queue->toArray(), ['item 1', 'item 2', 'item 3']);

        $queue = new Queue();
        $queue->enqueue('item 1')->enqueue('item 2')->enqueue('item 3');
        $this->assertSame('item 3', $queue->peek());
        $this->assertEquals($queue->toArray(), ['item 3', 'item 2', 'item 1']);
    }

    public function testDuplicateValues(): void {
        $item1 = 'item 1';

        $queue = new Queue();
        $queue->enqueue($item1)->enqueue($item1)->enqueue($item1);

        $this->assertEquals(3, $queue->size());
    }

    public function testOrder(): void {
        $item1 = 'item 1';
        $item2 = 'item 2';
        $item3 = 'item 3';
        $items = [$item1, $item2, $item3];

        $queue = new Queue($items);
        $polls = [];
        $iters = [];

        foreach ($queue as $element) {
            $iters[] = $element;
        }

        while (($item = $queue->poll()) !== null) {
            $polls[] = $item;
        }

        $this->assertEquals($iters, $polls);

        $queue->clear();
        $this->assertNull($queue->peek());
    }

    public function testContains(): void {
        $item1 = 'item 1';
        $item2 = 'item 2';
        $item3 = 'item 3';
        $items = [$item1, $item2];

        $queue = new Queue($items);

        $this->assertTrue($queue->contains($item2));
        $this->assertFalse($queue->contains($item3));
    }

    public function testClone(): void {
        $queue = new Queue([1, 2, 3, 4, 5, 6]);
        $clone = clone $queue;

        $this->assertTrue($clone instanceof Queue);
        $this->assertEquals($queue, $clone);
        $this->assertEquals($queue->toArray(), $clone->toArray());
        $this->assertNotSame($queue, $clone);
    }

    public function testMap(): void {
        $cb = function ($item) {
            return strtoupper($item);
        };

        $queue = new Queue(['item 1', 'item 2', 'item 3']);
        $this->assertEquals(array_map($cb, $queue->toArray()), $queue->map($cb)->toArray());
    }
}
