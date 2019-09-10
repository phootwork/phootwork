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

use phootwork\file\Directory;
use phootwork\file\File;
use phootwork\file\Path;
use PHPUnit\Framework\TestCase;

/**
 * Class LinkTest
 *
 * Class to test the behavior of the library with symbolic links.
 * Unfortunately, links are not supported in PHP streams so they can't be tested via vfsStream.
 *
 * @see https://wiki.php.net/rfc/linking_in_stream_wrappers
 *
 * @author Cristiano Cinotti
 */
class LinkTest extends TestCase {

	/** @var Directory */
	private $tempDir;

	public function setUp(): void {
		$this->tempDir = new Directory(sys_get_temp_dir() . '/phootwork');
		//if some errors, sometimes tearDown is not called
		if ($this->tempDir->exists()) {
			$this->tempDir->delete();
		}

		$this->tempDir->make();
	}

	public function tearDown(): void {
		$this->tempDir->delete();
		parent::tearDown();
	}

	public function testLink(): void {
		$origin = new Path(tempnam((string) $this->tempDir->getPathname(), 'orig'));
		$target = new File(tempnam((string) $this->tempDir->getPathname(), 'target'));
		$target->delete();
		$target = new Path($target->getPathname());

		$file = new File($origin);
		$file->touch();
		$file->linkTo($target);
		$link = $target->toFileDescriptor();

		$this->assertNull($file->getLinkTarget());
		$this->assertTrue($link->exists());
		$this->assertTrue($link->isLink());
		$this->assertTrue($origin->equals($link->getLinkTarget()));
	}

	public function testLinkToAdifferentLink(): void {
		$origin = new Path(tempnam((string) $this->tempDir->getPathname(), 'orig'));
		$target = new File(tempnam((string) $this->tempDir->getPathname(), 'target'));
		$target->delete();
		$target = new Path($target->getPathname());

		$file = new File($origin);
		$file->touch();
		$file->linkTo($target);
		$link = $target->toFileDescriptor();
		$this->assertTrue($link->exists());
		$this->assertTrue($link->isLink());

		$origin2 = new Path(tempnam((string) $this->tempDir->getPathname(), '2orig'));
		$file2 = new File($origin2);
		$file2->touch();
		$file2->linkTo($target);
		$link2 = $target->toFileDescriptor();

		$this->assertNull($file->getLinkTarget());
		$this->assertTrue($link2->exists());
		$this->assertTrue($link2->isLink());
		$this->assertFalse($origin->equals($link2->getLinkTarget()));
		$this->assertTrue($origin2->equals($link2->getLinkTarget()));
	}
}
