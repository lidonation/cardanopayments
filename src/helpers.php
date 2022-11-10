<?php

use Dotenv\Dotenv;

function loadEnvVariables()
{
    $dotenv = Dotenv::createImmutable(dirname(dirname(__FILE__)));
    $dotenv->load();
}

function env($variable) {
    if (!isset($_ENV[$variable])) {
        loadEnvVariables();
    }
    return $_ENV[$variable] ?? null;
}
