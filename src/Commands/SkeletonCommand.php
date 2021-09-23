<?php

namespace Stats4sd\FileUtil\Commands;

use Illuminate\Console\Command;

class FileUtilCommand extends Command
{
    public $signature = 'fileutil';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
