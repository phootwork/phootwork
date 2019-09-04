<?php declare(strict_types=1);
/**
 * This file is part of the Phootwork package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 * @copyright Thomas Gossmann
 */
namespace phootwork\file\tests;

use phootwork\file\Path;
use phootwork\lang\ArrayObject;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase {
    public function testBasicNaming(): void {
        $p = new Path('this/is/the/path/to/my/file.ext');

        $this->assertEquals('this/is/the/path/to/my', $p->getDirname());
        $this->assertEquals('file.ext', $p->getFilename());
        $this->assertEquals('ext', $p->getExtension());
        $this->assertEquals('this/is/the/path/to/my/file.ext', $p->getPathname());

        $p = new Path('another/path');
        $this->assertEmpty($p->getExtension());
        $p = $p->append('to');
        $this->assertEquals('another/path/to', $p->getPathname());
        $p = $p->append(new Path('my/stuff'));
        $this->assertEquals('another/path/to/my/stuff', $p->getPathname());

        $p = new Path('file:///this/is/the/path/to/my/file.ext');
        $this->assertTrue($p->isStream());
        $this->assertEquals('file:///this/is/the/path/to/my', $p->getDirname());
        $this->assertEquals('file.ext', $p->getFilename());
        $this->assertEquals('ext', $p->getExtension());
        $this->assertEquals('file:///this/is/the/path/to/my/file.ext', $p->getPathname());
    }

    public function testExtension(): void {
        $p = new Path('my/file.ext');
        $this->assertEquals('ext', $p->getExtension());
        $this->assertEquals('bla', $p->setExtension('bla')->getExtension());
        $this->assertEmpty($p->removeExtension()->getExtension());
    }

    public function testSegments(): void {
        $p = new Path('this/is/the/path/to/my/file.ext');

        $this->assertEquals(new ArrayObject(['this', 'is', 'the', 'path', 'to', 'my', 'file.ext']), $p->segments());
        $this->assertEquals(7, $p->segmentCount());
        $this->assertNull($p->segment(-1));
        $this->assertEquals('is', $p->segment(1));
        $this->assertEquals('file.ext', $p->lastSegment());
        $this->assertEquals('this/is/the', $p->upToSegment(3)->toString());
        $this->assertEquals('the/path/to/my/file.ext', $p->removeFirstSegments(2)->toString());
        $this->assertEquals('this/is/the/path/to', $p->removeLastSegments(2)->toString());
        $this->assertEquals('file.ext', $p->lastSegment());
        $this->assertEquals('', $p->upToSegment(0)->toString());
    }

    public function testSegmentsStream(): void {
        $p = new Path('ftp://this/is/the/path/to/my/file.ext');

        $this->assertEquals(new ArrayObject(['this', 'is', 'the', 'path', 'to', 'my', 'file.ext']), $p->segments());
        $this->assertEquals(7, $p->segmentCount());
        $this->assertNull($p->segment(-1));
        $this->assertEquals('is', $p->segment(1));
        $this->assertEquals('file.ext', $p->lastSegment());
        $this->assertEquals('this/is/the', $p->upToSegment(3)->toString());
        $this->assertEquals('the/path/to/my/file.ext', $p->removeFirstSegments(2)->toString());
        $this->assertEquals('this/is/the/path/to', $p->removeLastSegments(2)->toString());
        $this->assertEquals('file.ext', $p->lastSegment());
        $this->assertEquals('', $p->upToSegment(0)->toString());
    }

    public function testTrailingSlash(): void {
        $p = new Path('stairway/to/hell');

        $this->assertFalse($p->hasTrailingSeparator());
        $p->addTrailingSeparator();
        $this->assertTrue($p->hasTrailingSeparator());
        $p->removeTrailingSeparator();
        $this->assertFalse($p->hasTrailingSeparator());
    }

    public function testMatching(): void {
        $base = new Path('this/is/the/path/to/my/file.ext');
        $prefix = new Path('this/is/the');
        $anotherPath = new Path('this/is/another/path');

        $this->assertTrue($prefix->isPrefixOf($base));

        $this->assertEquals(3, $base->matchingFirstSegments($prefix));
        $this->assertEquals(2, $base->matchingFirstSegments($anotherPath));
        $this->assertEquals('/path/to/my/file.ext', $base->makeRelativeTo($prefix)->toString());
    }

    public function testAbsolute(): void {
        $win = new Path('c:\\\\windows');
        $this->assertTrue($win->isAbsolute());

        $unix = new Path('/etc');
        $this->assertTrue($unix->isAbsolute());

        $win = new Path('\\windows\\system');
        $this->assertTrue($win->isAbsolute());

        $null = new Path('');
        $this->assertFalse($null->isAbsolute());

        $current = new Path('./some/dir');
        $this->assertFalse($current->isAbsolute());

        $abs = new Path(__FILE__);
        $this->assertTrue($abs->isAbsolute());

        $stream = new Path('file:///Home/Documents');
        $this->assertTrue($stream->isAbsolute());
    }

    public function testEquals(): void {
        $current = new Path(__FILE__);
        $cwd = new Path(getcwd());
        $relative = new Path('.' . $current->makeRelativeTo($cwd));

        $this->assertTrue($current->equals($relative));

        // with virtual path
        $current = new Path('vfs://root/dir/file.ext');
        $relative = new Path('vfs://root/file.ext');
        $this->assertFalse($current->equals($relative));
        $this->assertFalse($current->equals($cwd));
    }

    public function testAppend(): void {
        $current = new Path('/home/user');
        $new = $current->append('path/to/append');
        $this->assertEquals('/home/user/path/to/append', $new->getPathname()->toString());

        $vfs = new Path('vfs://root/home/user');
        $newVfs = $vfs->append('path/to/append');
        $this->assertEquals('vfs://root/home/user/path/to/append', $newVfs->getPathname()->toString());

        //Is it a correct behavior?
        $current = new Path('/home/user/spiderman.txt');
        $new = $current->append('path/to/append');
        $this->assertEquals('/home/user/spiderman.txt/path/to/append', $new->getPathname()->toString());
    }

    public function testIsEmpty(): void {
        $path = new Path('');
        $this->assertTrue($path->isEmpty());
    }
}
