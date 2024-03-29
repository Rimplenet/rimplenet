<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitc48a0effe16e677caaae2cf3d1108544
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitc48a0effe16e677caaae2cf3d1108544', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitc48a0effe16e677caaae2cf3d1108544', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitc48a0effe16e677caaae2cf3d1108544::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
