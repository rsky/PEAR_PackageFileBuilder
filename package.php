#!/usr/bin/env php
<?php
/**
 * Package maker script.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

require __DIR__ . DIRECTORY_SEPARATOR . 'SplClassLoader.php';

use PEAR\PackageFileBuilder;
use PEAR\PackageFileBuilder\ConfigLoader\YamlLoader;

if (__FILE__ === realpath($_SERVER['argv'][0])) {
    set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());
    $classLoader = new SplClassLoader();
    $classLoader->register();

    error_reporting(E_ALL & ~(E_STRICT | E_DEPRECATED));
    PEAR::setErrorHandling(PEAR_ERROR_DIE);
    main($_SERVER['argc'], $_SERVER['argv']);
}

function main($argc, $argv)
{
    $command = ($argc > 1) ? $argv[1] : null;

    chdir(__DIR__);

    $loader = new YamlLoader();
    $pkgConfig = './package.yml';
    $pfmOptions = array('packagedirectory' => '.');

    $builder = new PackageFileBuilder($loader);
    $builder->setup($pkgConfig, $pfmOptions);
    $pfm = $builder->getPackageFileManager();

    $pfm->generateContents();

    if (('make' === $command) || ('archive' === $command)) {
        $pfm->writePackageFile();
        if ('archive' === $command) {
            passthru('pear package package.xml');
        }
    } else {
        $pfm->debugPackageFile();
    }
}
