<?php

namespace App\Modules\Shared\Actions;

use Illuminate\Support\Facades\File;

class DeleteImageAction
{
    /**
     * @var imagePath is a full path to image like: example: public_path($news->getOriginal('image')[app()->getLocale()]);
     */
    public static function deleteModelImages(string $imagePath)
    {
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $directory = dirname($imagePath);
        $filename = basename($imagePath);
        $mediumDirectory = $directory . '/medium';
        $mediumImagePath = $mediumDirectory . '/' . $filename;
        if (File::exists($mediumImagePath)) {
            File::delete($mediumImagePath);
        }
        $thumbnailDirectory = $directory . '/thumbnail';
        $thumbnailImagePath = $thumbnailDirectory . '/' . $filename;
        if (File::exists($thumbnailImagePath)) {
            File::delete($thumbnailImagePath);
        }
    }
}
