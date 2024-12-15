<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


if (!function_exists('sendError')) {

    function sendError(string $message, array $errors = [], int $code = 401)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => !empty($errors) ? $errors : null,
        ];

        return response()->json($response, $code);
    }

}

if (!function_exists('sendResponse')) {

    /**
     * Send a response with a standardized structure.
     *
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    function sendResponse(string $message, array $data = [], int $code = 200): JsonResponse
    {

        $response = [
            'success' => true,
            'message' => $message,
            'data' => !empty($data) ? $data : null,
        ];

        // Return the response
        return response()->json($response, $code);
    }
}

if (! function_exists('slugify')) {

    function slugify($text, string $divider = '-'): string
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}

if (! function_exists('deSlugify')) {

    function deSlugify($text, string $divider = '-'): string
    {

        // pascal case
        $text =  str_replace('_', ' ', ucwords($text, '_'));

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}

/**
 * Process and store an image, returning the filename.
 */

if(! function_exists('processImage')) {

    function processImage($image, $path): string
    {
        // Generate a unique file name
        $fileNameToStore = uniqid() . '-' . time() . '.' . $image->getClientOriginalExtension();

        // Ensure the path ends with a single slash
        $fullPath = rtrim($path, '/') . '/' . $fileNameToStore;

        try {
            // Save the image with public visibility
            Storage::disk('public')->put($fullPath, file_get_contents($image), ['visibility' => 'public']);

            return $fileNameToStore;
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Failed to store the image: ' . $e->getMessage());

            return "";
        }

    }


}

/**
 * Check if image is exist in given path and return true if exists.
 */

if(! function_exists('checkImageExist')) {

    function checkImageExist($path): bool
    {
        if (Storage::disk('public')->exists($path))
        {
            return true;
        }

        return false;
    }
}

