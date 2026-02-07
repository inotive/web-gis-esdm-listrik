<?php

namespace App\Helpers;

use File;

trait UploadFile
{

    function storeFile($file, $path)
    {
        if (! File::exists(storage_path('app/public/' . $path))) {
            File::makeDirectory(storage_path('app/public/' . $path), 0755, true, true);
        }

        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $fileNameToStore = $filename . '.' . $extension;
        $counter = 1;

        while (File::exists(storage_path('app/public/' . $path . '/' . $fileNameToStore))) {
            $fileNameToStore = $filename . ' (' . $counter . ').' . $extension;
            $counter++;
        }

        $file->storeAs($path, $fileNameToStore, 'public');

        return $fileNameToStore;
    }

    public function deleteFile($file, $path)
    {
        if (File::exists(storage_path('app/public/' . $path . '/' . $file))) {
            return File::delete(storage_path('app/public/' . $path . '/' . $file));
        }

        return false;
    }
}
