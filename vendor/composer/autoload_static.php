<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteea8b15ce8522adcefbbf440476019e2
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Application\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Application\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Application',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteea8b15ce8522adcefbbf440476019e2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteea8b15ce8522adcefbbf440476019e2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
