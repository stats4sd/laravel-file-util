<?php

namespace Stats4sd\FileUtil\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/**
 * This class is for File Upload feature
 */


class FileController extends Controller
{

    /**
     * Downloads the selected file from the selected disk
     *
     * Used to allow gates on download paths instead of always using public disk for easy downloads.
     *
     * @param String $path // the relative path to the file download
     * @param String $disk // the disk used to store the file (default = local)
     * @return void
     */
    public function download(String $path, String $disk = 'local')
    {
        return Storage::disk($disk)->download($path);
    }

    public function getImage(String $path, String $disk = 'local')
    {
        $fullPath = Storage::disk($disk)->path($path);

        $file = File::get($fullPath);
        $type = File::mimeType($fullPath);

        return Response::make($file, 200)->header("Content-Type", $type);
    }
}
