<?php
/**
 * Base class for testing LoaderInterface.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */

namespace tests\ConfigLoader;

use PHPUnit_FrameWork_TestCase;

abstract class BaseTestCase extends PHPUnit_FrameWork_TestCase
{
    protected $loader;

    protected function loadFileTestImpl($expected, $source)
    {
        $this->assertFileExists($source);
        $this->assertSame($expected, $this->loader->load($source));
    }

    protected function loadStreamTestImpl($expected, $source)
    {
        $this->assertInternalType('resource', $source);
        $this->assertSame($expected, $this->loader->load($source));
    }

    protected function loadStringTestImpl($expected, $source)
    {
        $this->assertInternalType('string', $source);
        $this->assertSame($expected, $this->loader->load($source));
    }
}
