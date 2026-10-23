<?php
// vendor/composer/autoload_real.php

class ComposerAutoloaderInitTablonAnuncios {
    private static $loader;

    public static function loadClassLoader($class) {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader() {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/ClassLoader.php';
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        
        // Registramos nuestro namespace App\ hacia la carpeta src/
        $loader->addPsr4('App\\', array(__DIR__ . '/../../src'));

        $loader->register(true);

        return $loader;
    }
}