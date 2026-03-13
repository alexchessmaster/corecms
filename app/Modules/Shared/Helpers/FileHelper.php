<?php

namespace App\Modules\Shared\Helpers;

use InvalidArgumentException;

class FileHelper
{

    // store the Files that come for message in the public folder
    // return $files(string) to store it in the $message->files
    // Usage:
    // $files = storeFilesAsString($request);
    // $message->files = $files;
    // $message->save();
    public static function upload($request, $fileInputName = 'file')
    {
        if (! $request->hasFile($fileInputName)) {
            return null;
            // throw new InvalidArgumentException;
        }

        request()->validate([
            $fileInputName => 'mimes:png,jpg,jpeg,avif,mp3,mp4,avi,mkv,txt,pdf,doc,docx,webp,webm,svg|max:100123'
        ]);

        $f = $request->file($fileInputName);
        $n = time() . rand(100000, 999999) . $f->getClientOriginalName();
        $f->move(public_path() . '/uploads', $n);
        $filepath = '/uploads/' . $n;

        return $filepath;
    }

    public static function addDomainPrefixIfValueIsAFile(string|null $value): string|null
    {
        if (empty($value) || str_starts_with($value, 'http')) {
            return $value;
        }

        $imageExtensions = ['gif', 'jpg', 'jpeg', 'avif','png', 'svg', 'bmp', 'tiff', 'ico', 'webp', 'pdf', 'doc', 'docx', 'txt', 'mp3', 'mp4', 'mkv', 'ogg', 'avi', 'wmv', 'm4v', 'octet-stream'];

        $fileExtension = pathinfo($value, PATHINFO_EXTENSION);

        if (in_array(strtolower($fileExtension), $imageExtensions)) {
            $pathParts = explode('/', $value);
            $filename = array_pop($pathParts);
            $encodedFilename = rawurlencode($filename);
            $value = implode('/', $pathParts) . '/' . $encodedFilename;

            return config('app.url') . '/' . ltrim($value, '/');
        }

        return $value;
    }

    /**
     * @param string $imagePath is absolute path. ex: public_path($news->getTranslation('image', app()->getLocale(), false));
     */
    public static function getMediumThumbnailImagePaths(string $imagePath): array
    {
        $originalPath = $imagePath;
        $filename = basename($originalPath);
        $directory = dirname($originalPath);
        $thumbnailDir = $directory . '/thumbnail';
        $mediumDir = $directory . '/medium';
        $thumbnailPath = $thumbnailDir . '/' . $filename;
        $mediumPath = $mediumDir . '/' . $filename;

        return ['medium' => $mediumPath, 'thumbnail' => $thumbnailPath];
    }
}
