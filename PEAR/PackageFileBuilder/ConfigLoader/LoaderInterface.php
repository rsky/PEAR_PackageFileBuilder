<?php
/**
 * Configuration loader interface.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace PEAR\PackageFileBuilder\ConfigLoader;

/**
 * Configuration loader interface.
 */
interface LoaderInterface
{
    /**
     * Loads configuration.
     *
     * @param   mixed   $source
     *
     * @return  array
     */
    public function load($source);
}
