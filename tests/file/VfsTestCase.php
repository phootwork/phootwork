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
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class VfsTestCase extends TestCase {
	private vfsStreamDirectory $root;

	public function getRoot(): vfsStreamDirectory {
		return $this->root ?? $this->root = vfsStream::setup();
	}

	public function createProject(): string {
		vfsStream::create([
			'prj' => [
				'composer.json' => '{}',
				'vendor' => [
					'autoload.php' => '// autoload'
				],
				'dir' => []
			]
		], $this->getRoot());

		return $this->root->url() . '/prj';
	}
}
