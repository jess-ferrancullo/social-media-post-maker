<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

trait UploadToBucket
{
    public function uploadToBucket(UploadedFile $file, string $socialMedia): string
    {
        $filename = $file->hashName();
        $path = "/public/$socialMedia/uploads/";

        //Create Folder if no folder yet
        if (!File::isDirectory(storage_path('app/' . $path))) {
            File::makeDirectory(storage_path('app/' . $path), 0777, true, true);
        }
        
        //Save file to folder
        $file->storeAs($path, $filename);

        return $path . $filename;
    }
}