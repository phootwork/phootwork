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
use phootwork\file\exception\FileException;
use phootwork\file\FileDescriptor;
use phootwork\lang\ArrayObject;

class DirectoryTest extends FilesystemTest {
	public function testCreateDirectory(): void {
		$dir = new Directory($this->root->url() . '/prj');
		$this->assertFalse($dir->exists());

		$dir->make();
		$this->assertTrue($dir->exists());
	}

	public function testCreateDirectoryWithFailure(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('Failed to create directory');

		$root = new Directory($this->root->url());
		$root->setMode(0555);

		$dir = new Directory($this->root->url() . '/prj');
		$dir->make();
	}

	public function testIterator(): void {
		$dir = new Directory($this->root->url() . '/prj');
		$dir->make();
		$path = $dir->toPath();
		$composer = $path->append('composer.json');
		$file = $composer->toFileDescriptor()->toFile();
		$file->write('{}');

		$vendor = $path->append('vendor');
		$folder = $vendor->toFileDescriptor()->toDirectory();
		$folder->make();

		$arr = new ArrayObject();
		foreach ($dir as $k => $file) {
			if (!$file->isDot()) {
				$this->assertTrue($file instanceof FileDescriptor);
				$arr[$k] = $file->getFilename();

				if ($file->isFile()) {
					$this->assertEquals('composer.json', $file->getFilename());
				}

				if ($file->isDir()) {
					$this->assertEquals('vendor', $file->getFilename());
				}
			}
		}

		$this->assertEquals(['composer.json', 'vendor'], $arr->sort()->toArray());
	}

	public function testDelete(): void {
		$prj = new Directory($this->createProject());
		$prj->delete();

		$this->assertFalse($prj->exists());
	}

	public function testDeleteWithFailure(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('Failed to delete directory');

		$prj = new Directory($this->createProject());
		$root = new Directory($this->root->url());
		$root->setMode(0555);
		$prj->delete();
	}

	public function testInode(): void {
		$dir = new Directory($this->createProject());
		//By now, vfsStream return always 0 when ask for inode
		//see https://github.com/bovigo/vfsStream/issues/119
		$this->assertEquals(0, $dir->getInode());
	}

	public function testNormalizePath(): void {
		$dir = new Directory('\\Documents\\MyDir');

		$this->assertEquals('/Documents/MyDir', $dir->getPathname()->toString());
	}
}
