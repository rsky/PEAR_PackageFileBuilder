<?php
/**
 * Configuration loader which uses Symfony's YAML component.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace PEAR\PackageFileBuilder\ConfigLoader;

require_once 'SymfonyComponents/YAML/sfYamlParser.php';

use sfYamlParser;

/**
 * Configuration loader class which uses Symfony's YAML component.
 */
class SfYamlLoader implements LoaderInterface
{
    /**
     * Instance of YAML parser.
     *
     * @var sfYamlParser
     */
    private $_parser;

    /**
     * Constructor.
     *
     * @param   sfYamlParser    $parser
     */
    public function __construct(sfYamlParser $parser)
    {
        $this->_parser = $parser;
    }

    /**
     * Loads configuration from YAML.
     *
     * @param   mixed   $source
     *
     * @return  array
     */
    public function load($source)
    {
        if (is_resource($source)) {
            $source = stream_get_contents($source);
        } elseif (file_exists($source)) {
            $source = file_get_contents($source);
        }
        return $this->_parser->parse($source);
    }
}
