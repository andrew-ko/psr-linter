<?php

namespace Psrlint\Util;

use Psrlint\Error;

function resolvePaths($paths)
{
    $files = [];

    foreach ($paths as $path) {
        $pathinfo = new \SplFileInfo($path);

        if ($pathinfo->isDir()) {
            $iter = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($pathinfo->getPathname(), \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iter as $p) {
                if ($p->isFile() && $p->getExtension() === 'php') {
                    $files[] = $p->getPathname();
                }
            }
        } elseif ($pathinfo->isFile() && $pathinfo->getExtension() === 'php') {
            $files[] = $pathinfo->getPathname();
        } else {
            throw new Error("No such file or directory: " . getcwd() . "/$path");
        }
    }

    return $files;
}
