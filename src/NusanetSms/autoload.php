<?php
/**
 * @description nusanetSms library
 * @version 1.0
 * file autoload ini dipakai jika tidak menggunakan composer
 */

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    throw new Exception('Untuk menggunakan nusanetSms Library Versi php harus > 5.4.0');
}

/**
 * @return void
 */
spl_autoload_register(function ($class) {
    $prefixNamespace = 'NusanetSms\\';
    $baseDir = __DIR__ . '/';
    $len = strlen($prefixNamespace);
    if (strncmp($prefixNamespace, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = rtrim($baseDir, '/') . '/' . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
