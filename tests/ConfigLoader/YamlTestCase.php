<?php
/**
 * Base class for testing YAML loaders.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */

namespace tests\ConfigLoader;

require_once __DIR__ . '/BaseTestCase.php';

abstract class YamlTestCase extends BaseTestCase
{
    /**
     * @test
     * @dataProvider provideYaml
     */
    public function testLoadFile($expected, $source)
    {
        $this->loadFileTestImpl($expected, $source);
    }

    /**
     * @test
     * @dataProvider provideYaml
     */
    public function testLoadStream($expected, $source)
    {
        $this->loadStreamTestImpl($expected, fopen($source, 'r'));
    }

    /**
     * @test
     * @dataProvider provideYaml
     */
    public function testLoadString($expected, $source)
    {
        $this->loadStringTestImpl($expected, file_get_contents($source));
    }

    /**
     * Provides tuples of expected result and path of the YAML file.
     */
    public function provideYaml()
    {
        $expected = array(
            'package' => array(
                'type' => 'php',
                'name' => 'PEAR_PackageFileBuilder',
                'license' => 'MIT License',
            ),
        );
        $source = __DIR__ . '/data/config.yml';
        return array(
            array($expected, $source),
        );
    }
}
