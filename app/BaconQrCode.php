<?php

namespace App\ThirdParty\BaconQrCode\src\Encoder;


// Load the Composer autoloader
require_once(APPPATH . 'ThirdParty/BaconQrCode/vendor/autoload.php');

// Register the namespace with the CodeIgniter autoloader
spl_autoload_register(function ($class) {
    // If the class uses the App\ThirdParty\BaconQrCode namespace, load it from the src directory
    $prefix = 'App\ThirdParty\BaconQrCode\\';
    $base_dir = APPPATH . 'ThirdParty/BaconQrCode/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Move to the next registered autoloader
        return;
    }
    // Get the relative class name
    $relative_class = substr($class, $len);
    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});