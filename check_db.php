<?php

require 'vendor/autoload.php';

$paths = new Config\Paths();
$app = new Config\App();

// Boot the framework
$bootstrap = new CodeIgniter\Boot($paths);
$bootstrap->boot();

$db = \Config\Database::connect();
$tables = $db->listTables();

echo "Tables in database:\n";
foreach ($tables as $table) {
    echo "- $table\n";
}

if (in_array('empresas', $tables)) {
    echo "\nTABLE 'empresas' EXISTS.\n";
} else {
    echo "\nTABLE 'empresas' DOES NOT EXIST.\n";
}
