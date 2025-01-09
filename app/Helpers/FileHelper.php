<?php

namespace App\Helpers;

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
            $fileInputName => 'mimes:png,jpg,jpeg,mp3,mp4,avi,mkv,txt,pdf,doc,docx,webp,webm,svg|max:100123'
        ]);
        
        $f = $request->file($fileInputName);
        $n = time() . rand(100000, 999999) . $f->getClientOriginalName();
        $f->move(public_path() . '/uploads', $n);
        $filepath = '/uploads/' . $n;
        
        return $filepath;
    }
}
