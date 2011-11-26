<?php
/**
 * Unit test for class YamlLoader.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */

namespace tests\ConfigLoader;

require_once __DIR__ . '/YamlTestCase.php';

use PEAR\PackageFileBuilder\ConfigLoader\YamlLoader;

class YamlLoaderTest extends YamlTestCase
{
    public function setUp()
    {
        if (extension_loaded('yaml')) {
            $this->loader = new YamlLoader();
            return;
        }
        $this->markTestSkipped('yaml PHP extension is not installed.');
    }
}
