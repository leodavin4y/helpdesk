<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller {

    public function get(string $name)
    {
        return Storage::download("files/{$name}");
    }
}