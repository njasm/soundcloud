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
    protected static $self = null;

    protected $available = [
        'RequestInterface' => '\Njasm\Soundcloud\Http\Request',
        'ResponseInterface' => '\Njasm\Soundcloud\Http\Response',
        'UrlBuilderInterface' => '\Njasm\Soundcloud\Http\Url\UrlBuilder',
        'AuthInterface' => '\Njasm\Soundcloud\Auth\Auth',
    ];

    public static function instance()
    {
        if (is_null(self::$self)) {
            self::$self = new self();
        }

        return self::$self;
    }
    /**
     * @param $interface
     * @param array $data
     * @return object
     * @throws \Exception
     */
    public function build($interface, array $data = [])
    {
        $interface = (string) $interface;
        $this->interfaceExists($interface);

        $className = $this->available[$interface];
        $this->interfaceClassExists($className);

        $class = new \ReflectionClass($className);
        $this->isInstantiable($class);

        return $class->newInstanceArgs($data);
    }

    /**
     * @param $interface
     * @return void
     * @throws \Exception
     */
    protected function interfaceExists($interface)
    {
        if (!in_array($interface, array_keys($this->available))) {
            throw new \Exception("Interface doesn't exist.");
        }
    }

    /**
     * @param string $className
     * @return void
     * @throws \Exception
     */
    protected function interfaceClassExists($className)
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
    protected function isInstantiable(\ReflectionClass $class)
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
    public function set($interface, $fqcn)
    {
        $interface = (string) $interface;
        $fqcn = (string) $fqcn;
        $this->interfaceClassExists($fqcn);
        $class = new \ReflectionClass($fqcn);
        $this->isInstantiable($class);

        $this->available[$interface] = $fqcn;
    }
}