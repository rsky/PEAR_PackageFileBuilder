<?php
/**
 * Utility for PEAR_PackageFileManager2.
 *
 * @package PEAR_PackageFileBuilder
 * @author Ryusuke SEKIYAMA <rsky0711@gmail.com>
 * @copyright Copyright (c) 2011 Ryusuke SEKIYAMA
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace PEAR;

use PEAR\PackageFileBuilder\ConfigLoader\LoaderInterface as ConfigLoader;
use PEAR_PackageFileManager2 as PackageFileManager;

class PackageFileBuilder
{
    /**
     * @var ConfigLoader
     */
    protected $loader;

    /**
     * @var \PEAR_PackageFileManager2
     */
    protected $pfm;

    /**
     * Builds a package file manager.
     *
     * @param ConfigLoader $loader
     * @param mixed $pkgConfig
     * @param array $pfmOptions
     *
     * @return \PEAR_PackageFileManager2
     */
    public static function build(ConfigLoader $loader,
                                 $pkgConfig,
                                 array $pfmOptions = array())
    {

        $builder = new PackageFileBuilder($loader);
        return $builder
            ->setup($pkgConfig, $pfmOptions)
            ->getPakageFileManager();
    }

    /**
     * Constructor.
     *
     * @param ConfigLoader $loader
     */
    public function __construct(ConfigLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Setup.
     *
     * @param mixed $pkgConfig
     * @param array $pfmOptions
     *
     * @return PackageFileBuilder $this
     */
    public function setup($pkgConfig, array $pfmOptions = array())
    {
        $pkgInfo = $this->loader->load($pkgConfig);

        if (array_key_exists('packageFileManagerOptions', $pkgInfo)) {
            $pfmOptions = array_merge(
                $pkgInfo['packageFileManagerOptions'], $pfmOptions);
        }

        $this->pfm = new PackageFileManager();
        $this->pfm->setOptions($pfmOptions);

        $this->setPackage($pkgInfo['package']);
        $this->setState($pkgInfo['state']);
        $this->addMaintainers($pkgInfo['maintainers']);

        if (array_key_exists('releases', $pkgInfo)) {
            foreach ($pkgInfo['releases'] as $release) {
                $this->addRelease($release);
            }
        } else {
            $this->addRelease();
        }

        if (array_key_exists('dependencies', $pkgInfo)) {
            $this->setDependencies($pkgInfo['dependencies']);
        }

        if (array_key_exists('tasks', $pkgInfo)) {
            $this->addTasks($pkgInfo['tasks']);
        }

        return $this;
    }

    /**
     * Returns PEAR_PackageFileManager2 object.
     *
     * @param void
     *
     * @return \PEAR_PackageFileManager2
     *
     * @throws \LogicException
     */
    public function getPakageFileManager()
    {
        if (!($this->pfm instanceof PackageFileManager)) {
            throw new \LogicException('Did not set up!');
        }
        return $this->pfm;
    }

    public function setPackage(array $package)
    {
        $pfm = $this->pfm;
        $pfm->setPackageType($package['type']);
        $pfm->setPackage($package['name']);
        $pfm->setChannel($package['channel']);
        $pfm->setSummary($package['summary']);
        $pfm->setDescription($package['description']);
        $this->setLicense($package['license']);
    }

    public function setLicense($license)
    {
        if (is_array($license)) {
            $this->pfmCall('setLicense',
                            $license,
                            array('name'),
                            array('uri', 'filesource'));
        } else {
            $this->pfm->setLicense($license);
        }
    }

    public function setState($state)
    {
        $pfm = $this->pfm;
        $pfm->setReleaseVersion($state['version']);
        $pfm->setReleaseStability($state['stability']);
        $pfm->setApiVersion($state['apiVersion']);
        $pfm->setApiStability($state['apiStability']);
        $pfm->setNotes($state['notes']);
    }

    public function addMaintainers(array $maintainers)
    {
        $pfm = $this->pfm;;

        foreach ($maintainers as $maintainer) {
            $role   = $maintainer['role'];
            $handle = $maintainer['handle'];
            $name   = $maintainer['name'];
            $email  = $maintainer['email'];
            $active = 'yes';
            if (array_key_exists('active', $maintainer)) {
                if (is_string($maintainer['active'])) {
                    $active = $maintainer['active'];
                } elseif ($params['active']) {
                    $active = 'yes';
                } else {
                    $active = 'no';
                }
            }
            $pfm->addMaintainer($role, $handle, $name, $email, $active);
        }
    }

    public function addRelease(array $release = array())
    {
        $pfm = $this->pfm;
        $pfm->addRelease();

        if (array_key_exists('osInstallCondition', $release)) {
            $pfm->setOsInstallCondition($release['osInstallCondition']);
        }

        if (array_key_exists('installAs', $release)) {
            foreach ($release['installAs'] as $path => $as) {
                $pfm->addInstallAs($path, $as);
            }
        }

        if (array_key_exists('ignoreToRelease', $release)) {
            foreach ($release['ignoreToRelease'] as $path) {
                $pfm->addIgnoreToRelease($path);
            }
        }
    }

    public function setDependencies(array $dependencies)
    {
        if (array_key_exists('php', $dependencies)) {
            $phpDep = $dependencies['php'];
            $this->setPhpDependency($phpDep);
        }

        if (array_key_exists('pearinstaller', $dependencies)) {
            $pearInstallerDep = $dependencies['pearinstaller'];
            $this->setPearInstallerDependency($pearInstallerDep);
        }

        if (array_key_exists('packages', $dependencies)) {
            $packageDeps = $dependencies['packages'];
            $this->addPackageDependencies($packageDeps);
        }
    }

    public function setPhpDependency($phpDep)
    {
        if (is_array($phpDep)) {
            $this->pfmCall('setPhpDep',
                            $phpDep,
                            array('min'),
                            array('max', 'exclude'));

        } else {
            $this->pfm->setPhpDep($phpDep);
        }
    }

    public function setPearInstallerDependency($pearInstallerDep)
    {
        if (is_array($pearInstallerDep)) {
            $this->pfmCall('setPearinstallerDep',
                            $pearInstallerDep,
                            array('min'),
                            array('max', 'recommended', 'exclude'));
        } else {
            $this->pfm->setPearinstallerDep($pearInstallerDep);
        }
    }

    public function addPackageDependencies(array $packageDeps)
    {
        $requirements = array('type', 'name', 'channel');
        $optionals = array('min', 'max', 'recommended', 'exclude',
                           'providesextension', 'nodefault');
        foreach ($packageDeps as $packageDep) {
            $this->pfmCall('addPackageDepWithChannel',
                            $packageDep,
                            $requirements,
                            $optionals);
        }
    }

    public function addTasks(array $tasks)
    {
        $pfm = $this->pfm;

        if (array_key_exists('replacements', $tasks)) {
            $this->addReplacements($tasks['replacements']);
        }

        if (array_key_exists('unixeol', $tasks)) {
            foreach ($tasks['unixeol'] as $path) {
                $pfm->addUnixEol($path);
            }
        }

        if (array_key_exists('windowseol', $tasks)) {
            foreach ($tasks['windowseol'] as $path) {
                $pfm->addWindowsEol($path);
            }
        }
    }

    public function addReplacements(array $replacements)
    {
        $pfm = $this->pfm;

        foreach ($replacements as $replacement) {
            $type = $replacement['type'];
            $from = $replacement['from'];
            $to   = $replacement['to'];
            if (array_key_exists('path', $replacement)) {
                $path = $replacement['path'];
                $pfm->addReplacement($path, $type, $from, $to);
            } else {
                $pfm->addGlobalReplacement($type, $from, $to);
            }
        }
    }

    protected function pfmCall($methodName,
                                array $parameters,
                                array $requirements,
                                array $optionals,
                                $default = false)
    {
        $callable = array($this->pfm, $methodName);
        $arguments = array();

        foreach ($requirements as $key) {
            $arguments[] = $parameters[$key];
        }

        foreach ($optionals as $key) {
            if (array_key_exists($key, $parameters)) {
                $arguments[] = $parameters[$key];
            } else {
                $arguments[] = $default;
            }
        }

        return call_user_func_array($callable, $arguments);
    }
}
