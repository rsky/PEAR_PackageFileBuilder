<?php
/**
 * Configuration loader which uses PECL yaml extension.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace PEAR\PackageFileBuilder\ConfigLoader;

use Symfony\Component\Yaml\Parser;

/**
 * Configuration loader class which uses PECL yaml extension.
 */
class YamlLoader implements LoaderInterface
{
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
        return yaml_parse($source);
    }
}
