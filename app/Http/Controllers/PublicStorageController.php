<?php

namespace App\Http\Controllers;

class PublicStorageController extends Controller
{
    public function show(string $path)
    {
        $publicStorageRoot = realpath(storage_path('app/public'));
        $filePath = realpath(storage_path('app/public/' . $path));

        abort_unless(
            $publicStorageRoot && $filePath && str_starts_with($filePath, $publicStorageRoot) && is_file($filePath),
            404
        );

        return response()->file($filePath);
    }
}
