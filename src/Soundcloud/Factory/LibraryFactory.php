<?php

namespace Njasm\Soundcloud\Factory;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 * @since       3.0.0
 */

class LibraryFactory
{
    protected static $available = [
        'RequestInterface' => '\Njasm\Soundcloud\Http\Request',
        'ResponseInterface' => '\Njasm\Soundcloud\Http\Response',
        'UrlBuilderInterface' => '\Njasm\Soundcloud\Http\Url\UrlBuilder',
        'AuthInterface' => '\Njasm\Soundcloud\Auth\Auth',
    ];

    /**
     * @param $interface
     * @param array $data
     * @return object
     * @throws \Exception
     */
    public static function build($interface, array $data = [])
    {
        $interface = (string) $interface;
        self::interfaceExists($interface);

        $className = self::$available[$interface];
        self::interfaceClassExists($className);

        $class = new \ReflectionClass($className);
        self::isInstantiable($class);

        return $class->newInstanceArgs($data);
    }

    /**
     * @param $interface
     * @return void
     * @throws \Exception
     */
    protected static function interfaceExists($interface)
    {
        if (!in_array($interface, array_keys(self::$available))) {
            throw new \Exception("Interface doesn't exist.");
        }
    }

    /**
     * @param string $className
     * @return void
     * @throws \Exception
     */
    protected static function interfaceClassExists($className)
    {
        if (!class_exists($className)) {
            throw new \Exception("$className Class doesn't exist.");
        }
    }

    /**
     * @param \ReflectionClass $class
     * @return void
     * @throws \Exception
     */
    protected static function isInstantiable(\ReflectionClass $class)
    {
        if (!$class->isInstantiable()) {
            throw new \Exception("{$class->getName()} isn't instantiable.");
        }
    }

    /**
     * @param $interface
     * @param $fqcn
     * @return void
     * @throws \Exception
     */
    public static function set($interface, $fqcn)
    {
        $interface = (string) $interface;
        $fqcn = (string) $fqcn;
        self::interfaceClassExists($fqcn);
        $class = new \ReflectionClass($fqcn);
        self::isInstantiable($class);

        self::$available[$interface] = $fqcn;
    }
}