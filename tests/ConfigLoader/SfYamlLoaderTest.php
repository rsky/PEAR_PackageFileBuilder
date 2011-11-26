<?php
/**
 * Unit test for class SfYamlLoader.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */

namespace tests\ConfigLoader;

require_once __DIR__ . '/YamlTestCase.php';

use PEAR\PackageFileBuilder\ConfigLoader\SfYamlLoader;
use sfYamlParser;

class SfYamlLoaderTest extends YamlTestCase
{
    public function setUp()
    {
        foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
            if (file_exists($path . '/SymfonyComponents/YAML/sfYamlParser.php')) {
                $this->loader = new SfYamlLoader(new sfYamlParser());
                return;
            }
        }
        $this->markTestSkipped('pear.symfony-project.com/YAML is not installed.');
    }
}
