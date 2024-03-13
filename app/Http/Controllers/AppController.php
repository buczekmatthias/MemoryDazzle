<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PostFile;
use App\Services\AppServices;
use App\Services\FilesServices;

class AppController extends Controller
{
    public function homepage()
    {
        return inertia('Homepage', AppServices::getHomepageData());
    }

    public function downloadFile(PostFile $postFile)
    {
        return FilesServices::downloadFile($postFile);
    }
}
