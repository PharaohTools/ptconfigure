<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita62e45aeae39330c63b7a32e6b8ad798
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Process\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita62e45aeae39330c63b7a32e6b8ad798::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita62e45aeae39330c63b7a32e6b8ad798::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
