<?php
/**
 * Preload file for phpunitrunner.
 *
 * Usage:
 *  phpunitrunner -R -c -p preload.php tests
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());
spl_autoload_register();
