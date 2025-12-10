<?php
// config.php
// Database configuration
// Using Environment Variables for security

// Simple .env loader for local development
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'dbname');
define('DB_USER', getenv('DB_USER') ?: 'dbuser');
define('DB_PASS', getenv('DB_PASS') ?: 'dbpass');
define('POKEMON_API_KEY', getenv('POKEMON_API_KEY') ?: '');
