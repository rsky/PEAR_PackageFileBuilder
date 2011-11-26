<?php
/**
 * Unit test for class Sf2YamlLoader.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */

namespace tests\ConfigLoader;

require_once __DIR__ . '/YamlTestCase.php';

use PEAR\PackageFileBuilder\ConfigLoader\Sf2YamlLoader;
use Symfony\Component\Yaml\Parser;

class Sf2YamlLoaderTest extends YamlTestCase
{
    public function setUp()
    {
        foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
            if (file_exists($path . '/Symfony/Component/Yaml/Parser.php')) {
                $this->loader = new Sf2YamlLoader(new Parser());
                return;
            }
        }
        $this->markTestSkipped('pear.symfony.com/Yaml is not installed.');
    }
}
