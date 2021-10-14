<?php

namespace Stats4sd\FileUtil;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Stats4sd\FileUtil\Commands\FileUtilCommand;

/**
 * This package does not have configuration file and migration file at this momment
 * Keep them in below Service Provider for possible future use
 */

 
class FileUtilServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-file-util')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_fileutil_table')
            ->hasCommand(FileUtilCommand::class);
    }
}
