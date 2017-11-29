<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4d1aac20cabf60959fc9162f214fc69b
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rollbar\\' => 8,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rollbar\\' => 
        array (
            0 => __DIR__ . '/..' . '/rollbar/rollbar/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4d1aac20cabf60959fc9162f214fc69b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4d1aac20cabf60959fc9162f214fc69b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
