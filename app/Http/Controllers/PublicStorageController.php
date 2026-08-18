<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicStorageController extends Controller
{
    public function show(Request $request, string $path)
    {
        $publicStorageRoot = realpath(storage_path('app/public'));
        $filePath = realpath(storage_path('app/public/' . $path));

        abort_unless(
            $publicStorageRoot && $filePath && str_starts_with($filePath, $publicStorageRoot) && is_file($filePath),
            404
        );

        $response = response()->file($filePath);
        $response->setAutoEtag();
        $response->setAutoLastModified();
        $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

        $response->isNotModified($request);

        return $response;
    }
}
