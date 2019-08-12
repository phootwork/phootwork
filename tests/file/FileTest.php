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

use org\bovigo\vfs\vfsStream;
use phootwork\file\Directory;
use phootwork\file\exception\FileException;
use phootwork\file\File;
use phootwork\file\Path;

class FileTest extends FilesystemTest {

	public function testReadWrite(): void {
		$json = '{"hello":"world!"}';
		$file = new File($this->root->url() . '/dir/composer.json');
		$file->write($json);

		$this->assertEquals($json, $file->read());
	}

	public function testReadUnreadableFile(): void
	{
		$this->expectException(FileException::class);
		$this->expectExceptionMessage("You don't have permissions to access nonreadable.txt file");

		$testFile = vfsStream::newFile('nonreadable.txt', 000)->at($this->root)->setContent('I am not readable.');
		$file = new File($testFile->url());
		$file->read();
	}

	public function testContentsFromNonExistingFile(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('File does not exist: composer.json');

		$file = new File($this->root->url() . '/composer.json');
		$file->read();
	}
	
	public function testMove(): void {
		$file = new File($this->root->url() . '/dir/composer.json');
		$file->write('{}');
		$file->move($this->root->url() . '/composer.json');
		
		$this->assertTrue(file_exists($this->root->url() . '/composer.json'));
		$this->assertFalse(file_exists($this->root->url() . '/dir/composer.json'));
	}

	public function testMoveWithFailure(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('Failed to move vfs://root/composer.json to vfs://root/dir/composer.json');

		$dir = new Directory($this->root->url() . '/dir');
		$dir->make(0555);
		$file = new File($this->root->url() . '/composer.json');
		$file->write('{}');
		$file->move(new Path($this->root->url() . '/dir/composer.json'));
	}
	
	public function testCopy(): void {
		$file = new File($this->root->url() . '/dir/composer.json');
		$file->write('{}');
		$file->copy($this->root->url() . '/composer.json');
	
		$this->assertTrue(file_exists($this->root->url() . '/composer.json'));
		$this->assertTrue(file_exists($this->root->url() . '/dir/composer.json'));
		
		$a = new File($this->root->url() . '/dir/composer.json');
		$b = new File($this->root->url() . '/composer.json');
		
		$this->assertEquals($a->read(), $b->read());
	}

	public function testCopyWithFailure(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('Failed to copy vfs://root/composer.json to vfs://root/dir/composer.json');

		$dir = new Directory($this->root->url() . '/dir');
		$dir->make(0555);
		$file = new File($this->root->url() . '/composer.json');
		$file->write('{}');
		$file->copy(new Path($this->root->url() . '/dir/composer.json'));
	}

	public function testTouchWithFailure(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('Failed to touch file at vfs://root/dir/composer.json');

		$dir = new Directory($this->root->url() . '/dir');
		$dir->make(0555);
		$file = new File($this->root->url() . '/dir/composer.json');
		$file->touch();
	}

	public function testTouchWithDateTime(): void {
		$createDate = new \DateTime('2018-08-27');
		$modDate = new \DateTime('2018-08-29');
		$dir = new Directory($this->root->url() . '/dir');
		$dir->make();
		$file = new File($this->root->url() . '/dir/composer.json');
		$file->touch($createDate, $modDate);

		$this->assertEquals($createDate, $file->getCreatedAt());
		$this->assertEquals($modDate, $file->getLastAccessedAt());
		$this->assertEquals($createDate, $file->getModifiedAt());
	}
	
	public function testDelete(): void {
		$dir = new Directory($this->root->url() . '/dir');
		$dir->make();
		$file = new File($this->root->url() . '/dir/composer.json');
		$file->touch();
		
		$this->assertTrue($file->exists());
		$file->delete();
		$this->assertFalse($file->exists());
	}

	public function testDeleteNotExistentFileThrowsException(): void {
		$this->expectException(FileException::class);
		$this->expectExceptionMessage('Failed to delete file');

		$file = new File($this->root->url() . '/non-existent-file.txt');
		$this->assertFalse($file->exists());
		$file->delete();
	}

	public function testGetGroup(): void {
		$stream = vfsStream::newFile('myfile.txt')->at($this->root)->chgrp(25);
		$file = new File($stream->url());

		$this->assertEquals(25, $file->getGroup());
	}

	public function testGetOwner(): void {
		$stream = vfsStream::newFile('myfile.txt')->at($this->root)->chown(3);
		$file = new File($stream->url());

		$this->assertEquals(3, $file->getOwner());
	}

	public function testGetPermissions(): void {
		$stream = vfsStream::newFile('myfile.txt', 0755)->at($this->root);
		$file = new File($stream->url());

		// see https://www.php.net/manual/en/function.fileperms.php examples
		$this->assertEquals('0755', substr(sprintf('%o', $file->getPermissions()), -4));
		$this->assertTrue($file->isExecutable());
		$this->assertTrue($file->isWritable());
	}

	public function testIsWritable(): void {
		$stream = vfsStream::newFile('myfile.txt', 0755)->at($this->root);
		$file = new File($stream->url());
		$this->assertTrue($file->isWritable());

		$stream->chmod(0100);
		$this->assertFalse($file->isWritable());
	}

	public function testIsExecutable(): void {
		$stream = vfsStream::newFile('myfile.txt', 0755)->at($this->root);
		$file = new File($stream->url());
		$this->assertTrue($file->isExecutable());

		$stream->chmod(0200);
		$this->assertFalse($file->isExecutable());
	}

	public function testSetGroup(): void {
		$stream = vfsStream::newFile('myfile.txt')->at($this->root);
		$file = new File($stream->url());
		$file->setGroup(vfsStream::GROUP_USER_1);
		$this->assertEquals(vfsStream::GROUP_USER_1, $stream->getGroup());
	}

	public function testSetOwner(): void {
		$stream = vfsStream::newFile('myfile.txt')->at($this->root);
		$file = new File($stream->url());
		$file->setOwner(vfsStream::OWNER_USER_1);
		$this->assertEquals(vfsStream::OWNER_USER_1, $stream->getUser());
	}
}
