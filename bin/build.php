#!/usr/bin/env php
<?php

use Webcito\BsUiBundle\Build;

$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',      // when executed in the bundle repo itself
    __DIR__ . '/../../../autoload.php',       // when run as vendor/webcito/bs-ui-bundle/bin/build.php
];

$autoloadFound = false;

foreach ($autoloadFiles as $autoloadFile) {
    if (is_file($autoloadFile)) {
        require_once $autoloadFile;
        $autoloadFound = true;
        break;
    }
}

if (!$autoloadFound) {
    fwrite(STDERR, "Composer autoload.php not found.\n");
    fwrite(STDERR, "Checked:\n");

    foreach ($autoloadFiles as $autoloadFile) {
        fwrite(STDERR, " - $autoloadFile\n");
    }

    exit(1);
}

if (!class_exists(Build::class)) {
    fwrite(STDERR, "Build class not found. Run composer dump-autoload or check composer.json autoload.\n");
    exit(1);
}

Build::bundle();