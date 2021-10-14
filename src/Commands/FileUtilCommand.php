<?php

namespace Stats4sd\FileUtil\Commands;

use Illuminate\Console\Command;

/**
 * This package does not use this feature at this moment,
 * Keep this file for possible future use
 */


class FileUtilCommand extends Command
{
    public $signature = 'fileutil';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
