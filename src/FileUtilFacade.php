<?php

namespace Stats4sd\FileUtil;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Stats4sd\FileUtil\FileUtil
 */
class FileUtilFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-file-util';
    }
}
