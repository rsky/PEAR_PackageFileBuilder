<?php
/**
 * Configuration loader which uses Symfony2's Yaml component.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace PEAR\PackageFileBuilder\ConfigLoader;

use Symfony\Component\Yaml\Parser;

/**
 * Configuration loader class which uses Symfony2's Yaml component.
 */
class Sf2YamlLoader implements LoaderInterface
{
    /**
     * Instance of YAML parser.
     *
     * @var Symfony\Component\Yaml\Parser
     */
    private $_parser;

    /**
     * Constructor.
     *
     * @param   Symfony\Component\Yaml\Parser   $parser
     */
    public function __construct(Parser $parser)
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
