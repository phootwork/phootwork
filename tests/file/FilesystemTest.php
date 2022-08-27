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

abstract class FilesystemTest extends TestCase {
	/** @var vfsStreamDirectory */
	protected $root;

	public function setUp(): void {
		$this->root = vfsStream::setup();
	}

	protected function createProject(): string {
		vfsStream::create([
			'prj' => [
				'composer.json' => '{}',
				'vendor' => [
					'autoload.php' => '// autoload'
				],
				'dir' => []
			]
		]);

		return $this->root->url() . '/prj';
	}
}
