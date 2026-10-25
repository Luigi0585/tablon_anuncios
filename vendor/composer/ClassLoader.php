<?php
// vendor/composer/ClassLoader.php

namespace Composer\Autoload;

class ClassLoader {
    private $prefixDirsPsr4 = array();

    public function addPsr4($prefix, $paths) {
        $this->prefixDirsPsr4[$prefix] = (array) $paths;
    }

    public function register($prepend = false) {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    public function loadClass($class) {
        if ($file = $this->findFile($class)) {
            require_once $file;
            return true;
        }
        return false;
    }

    public function findFile($class) {
        foreach ($this->prefixDirsPsr4 as $prefix => $dirs) {
            if (0 === strpos($class, $prefix)) {
                $relativeClass = substr($class, strlen($prefix));
                $logicalPath = str_replace('\\', '/', $relativeClass) . '.php';
                foreach ($dirs as $dir) {
                    $file = $dir . '/' . $logicalPath;
                    if (file_exists($file)) {
                        return $file;
                    }
                }
            }
        }
        return false;
    }
}