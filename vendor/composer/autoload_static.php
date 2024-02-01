<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1c5876f5b80bc23ed33a1e9e10860527
{
    public static $files = array (
        'd833fc4ef1ba77843ce6537ef2292569' => __DIR__ . '/../..' . '/helpers/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Viserlab\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Viserlab\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controllers',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1c5876f5b80bc23ed33a1e9e10860527::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1c5876f5b80bc23ed33a1e9e10860527::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1c5876f5b80bc23ed33a1e9e10860527::$classMap;

        }, null, ClassLoader::class);
    }
}
