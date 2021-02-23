<?php

require_once 'vendor/autoload.php';

$appConfig = new \JelteV\ApplicationConfiguration\ApplicationConfiguration(__DIR__);

$success = $appConfig->load(
    [
        '/tests/resources/files/env/bookstore.env',
        '/tests/resources/files/xml/bookstore.xml',
        '/tests/resources/files/yaml/bookstore.yml',
        '/tests/resources/files/json/bookstore.json',
        '/tests/resources/files/env/',
    ]
);

if (!$success) {
    echo "Could not load application settings.";
    exit(1);
}

var_dump($appConfig->getSettings());
