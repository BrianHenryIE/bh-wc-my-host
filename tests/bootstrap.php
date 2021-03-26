<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.secret' );
$envs = $dotenv->load();
foreach( $envs as $name => $value ) {
    define( $name, $value );
}