<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc553c04d08497d0fdf0f29e328e573fa
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Livro\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Livro\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Lib/Livro',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/App/Control',
        1 => __DIR__ . '/../..' . '/App/Model',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc553c04d08497d0fdf0f29e328e573fa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc553c04d08497d0fdf0f29e328e573fa::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInitc553c04d08497d0fdf0f29e328e573fa::$fallbackDirsPsr4;

        }, null, ClassLoader::class);
    }
}
