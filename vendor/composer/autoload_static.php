<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9dcd85203ce3970b4b7d8630bb30ccd3
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9dcd85203ce3970b4b7d8630bb30ccd3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9dcd85203ce3970b4b7d8630bb30ccd3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9dcd85203ce3970b4b7d8630bb30ccd3::$classMap;

        }, null, ClassLoader::class);
    }
}
