<?php
/**
 * Bootstrap file for PHPUnit.
 *
 * Usage:
 *  phpunit --colors --bootstrap bootstrap.php tests
 *    OR
 *  phpunitrunner -c -p bootstrap.php -R tests
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @ignore
 */

set_include_path(implode(PATH_SEPARATOR, array(
    '@DATA_DIR@' . DIRECTORY_SEPARATOR . '@PACKAGE_NAME@',
    __DIR__,
    get_include_path(),
)));

require 'SplClassLoader.php';
$classLoader = new SplClassLoader();
$classLoader->register();

// Declare unused PHPUnit extension classes
// which may not be installed.
class PHPUnit_Extensions_Database_TestCase {}
class PHPUnit_Extensions_SeleniumTestCase {}
