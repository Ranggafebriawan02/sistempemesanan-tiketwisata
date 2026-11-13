<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "PHP Runtime is WORKING on Vercel!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Available Extensions: " . implode(', ', get_loaded_extensions()) . "<br>";
echo "PDO Drivers: " . implode(', ', pdo_drivers()) . "<br>";
echo "OpenSSL: " . (extension_loaded('openssl') ? 'YES' : 'NO') . "<br>";
