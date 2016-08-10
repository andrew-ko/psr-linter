<?php

namespace Psrlint\Util;

use Psrlint\Error;

function resolvePaths($paths, $options)
{
    $ignoredPaths = array_map(function ($el) {
        return realpath($el);
    }, $options['--ignore'] ?? []);

    $files = [];

    foreach ($paths as $path) {
        $pathinfo = new \SplFileInfo($path);

        if (in_array($pathinfo->getRealPath(), $ignoredPaths)) {
            continue;
        }

        if ($pathinfo->isDir()) {
            $dir = new \RecursiveDirectoryIterator($pathinfo->getRealPath(), \RecursiveDirectoryIterator::SKIP_DOTS);

            $filtered = new \RecursiveCallbackFilterIterator(
                $dir,
                function ($curr) use ($ignoredPaths) {
                    return !in_array($curr->getRealPath(), $ignoredPaths);
                }
            );

            $iter = new \RecursiveIteratorIterator($filtered, \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($iter as $p) {
                if ($p->isFile() && $p->getExtension() === 'php') {
                    $files[] = $p->getPathname();
                }
            }
        } elseif ($pathinfo->isFile() && $pathinfo->getExtension() === 'php') {
            $files[] = $pathinfo->getRealPath();
        } else {
            throw new Error("No such file or directory: " . getcwd() . "/$path");
        }
    }

    return $files;
}
