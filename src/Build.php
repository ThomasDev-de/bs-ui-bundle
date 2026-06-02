<?php

namespace Webcito\BsUiBundle;

use RuntimeException;

class Build
{
    public static function bundle(): void
    {
        $vendorDir = self::getVendorDir();

        $packages = [
            'webcito/jquery-bs-confirm',
            'webcito/bs-prompt',
            'webcito/bs-datepicker',
            'webcito/bs-markdown-editor',
            'webcito/jquery-select-suggest',
            'webcito/bs-color-picker',
            'webcito/bs-toast',
            'webcito/bs-timepicker',
            'webcito/bs-phone-input',
            'webcito/bs-select',
            'webcito/bs-touchspin',
            'webcito/bs-password',
            'webcito/bs-emoji-picker',
            'webcito/jquery-bs-circle-progress',
            'webcito/jquery-typing-events',
        ];

        $targetDir = dirname(__DIR__) . '/dist';
        $targetFile = $targetDir . '/webcito-bs-ui-bundle.min.js';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $bundle = "/*! Webcito Bootstrap UI Bundle */\n";
        $missing = [];

        foreach ($packages as $package) {
            $matches = glob($vendorDir . '/' . $package . '/dist/*.min.js');

            if (!$matches) {
                $missing[] = $package;
                continue;
            }

            sort($matches);

            foreach ($matches as $file) {
                $bundle .= "\n;/* $package: " . basename($file) . " */\n";
                $bundle .= trim(file_get_contents($file)) . "\n";
            }
        }

        if ($missing !== []) {
            throw new RuntimeException(
                "Missing dist/*.min.js for packages:\n - " . implode("\n - ", $missing)
            );
        }

        file_put_contents($targetFile, $bundle);

        echo "Created bundle: $targetFile\n";
    }

    private static function getVendorDir(): string
    {
        $candidates = [
            dirname(__DIR__) . '/vendor',      // bundle repo itself
            dirname(__DIR__, 3),               // installed under vendor/webcito/bs-ui-bundle
        ];

        foreach ($candidates as $candidate) {
            if (is_dir($candidate . '/webcito')) {
                return $candidate;
            }
        }

        throw new RuntimeException('Vendor directory not found.');
    }
}