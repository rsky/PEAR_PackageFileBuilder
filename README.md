# PEAR_PackageFileBuilder

Utility for PEAR_PackageFileManager2.

Help to make PEAR package with using configuration file.


## Examples

This is a package definition file of PEAR_PackageFileBuilder itself.

```yaml
package:
  type: 'php'
  name: 'PEAR_PackageFileBuilder'
  channel: '__uri'
  summary: 'Utility for PEAR_PackageFileManager2.'
  description: 'Help to make PEAR package with using configuration file.'
  license:
    name: 'MIT License'
    uri: 'http://www.opensource.org/licenses/mit-license.php'

state:
  version: '0.1.0'
  stability: 'alpha'
  apiVersion: '0.1.0'
  apiStability: 'alpha'
  notes: 'Initial release'

maintainers:
  -
    role: 'lead'
    handle: 'rsk'
    name: 'Ryusuke SEKIYAMA'
    email: 'rsky0711@gmail.com'

dependencies:
  php: '5.3.0'
  pearinstaller: '1.4.0'
  packages:
    -
      type: 'optional'
      name: 'Yaml'
      channel: 'pear.symfony.com'
      min: '2.0.0'
    -
      type: 'optional'
      name: 'YAML'
      channel: 'pear.symfony-project.com'
      min: '1.0.0'
    -
      type: 'optional'
      name: 'yaml'
      channel: 'pecl.php.net'
      min: '1.0.0'

tasks:
  replacements:
    -
      path: 'bootstrap.php'
      type: 'pear-config'
      from: '@DATA_DIR@'
      to: 'data_dir'
    -
      path: 'bootstrap.php'
      type: 'package-info'
      from: '@PACKAGE_NAME@'
      to: 'name'

packageFileManagerOptions:
  filelistgenerator: 'file'
  baseinstalldir: '/'
  simpleoutput: true
  dir_roles:
    'examples': 'doc'
    'tests': 'test'
  ignore:
    - 'package.php'
    - 'package.xml'
    - 'package.yml'
    - '*.tgz'
  exceptions:
    'LICENSE': 'doc'
    'README.md': 'doc'
    'SplClassLoader.php': 'data'
    'bootstrap.php': 'test'
    'phpunit.xml.dist': 'test'
```

And make PEAR package with this script.

    $ php package.php archive

```php
<?php
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
```

