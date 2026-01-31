<?php

namespace App\Helpers;

use App\Models\MediaStorage;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    public static function StoreMedia(
        $file,
        $folder = "images/",
        $diskType = "public_uploads"
    ){
        $extension = $file->getClientOriginalExtension();
        $filename = md5(time()).".".$extension;
        $disk = Storage::disk($diskType);
        $fullPath = $folder.$filename;
        $disk->put($fullPath, fopen($file, 'r+'),'public');
        return MediaStorage::create([
            'name'=>$filename,
            'extension'=>$extension,
            'path'=>$folder,
            'url'=>'uploads/'.$fullPath,
            'disk'=>$diskType
        ]);
    }

    public static function revert_media($id = null)
    {
        $media = MediaStorage::find($id);
        if($media)
        {
           try{
               $path = $media->path.$media->filename;
               $disk = Storage::disk(config('settings.media_disk','public_uploads'));
               if ($disk->exists($path))
               {
                   $disk->delete($path);
                   $media->delete();
                   return true;
               }
           }
           catch (\Exception $e){ return $e->getMessage(); }
        }
        return false;
    }

}
