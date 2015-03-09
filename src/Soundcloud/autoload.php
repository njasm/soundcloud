<?php
/**
 * Autoloader provided for Njasm Soundcloud Library.
 */

spl_autoload_register(
    function ($class) {
        $prefix = 'Njasm\\Soundcloud\\';
        $base_dir = __DIR__ . '/';
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            // move to the next registered autoloader
            return;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
);
