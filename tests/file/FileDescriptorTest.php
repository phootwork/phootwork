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
use phootwork\file\FileDescriptor;
use phootwork\file\Path;
use phootwork\lang\Text;

class FileDescriptorTest extends FilesystemTest {
	
	public function testTypes(): void {
		$this->assertTrue(Directory::create('') instanceof Directory);
		$this->assertTrue(File::create('') instanceof File);
		$this->assertTrue(FileDescriptor::create('') instanceof FileDescriptor);
		
		$this->assertTrue(is_string(File::create('/path/to/dir')->getPathname()));
		$this->assertTrue(is_string(Directory::create(new Path('/path/to/dir'))->getPathname()));
		$this->assertTrue(is_string(FileDescriptor::create(new Text('/path/to/dir'))->getPathname()));
	}

	public function testNames(): void {
		$file = new File($this->root->url() . '/dir/composer.json');

		$this->assertEquals('composer.json', $file->getFilename());
		$this->assertEquals($this->root->url() . '/dir', $file->getDirname());
		$this->assertEquals($this->root->url() . '/dir/composer.json', $file->getPathname());
		$this->assertEquals($this->root->url() . '/dir/composer.json', ''.$file);
		$this->assertEquals('json', $file->getExtension());
		
		$dir = new Directory($this->root->url() . '/dir');
		$this->assertEquals($this->root->url() . '/dir', ''.$dir);
		
		$desc = new FileDescriptor($this->root->url() . '/dir/composer.json');
		$this->assertEquals($this->root->url() . '/dir/composer.json', ''.$desc);
	}
}
