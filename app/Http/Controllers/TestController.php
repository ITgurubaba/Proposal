<?php

namespace App\Http\Controllers;

use App\Jobs\BackupMediaDbJob;
use App\Jobs\BackupMediaJob;
use App\Models\BackupImage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function Index(Request $request)
    {
//        $disk = Storage::disk('public_main');

        $data = BackupImage::where('status',0)->count();

        dd($data);

//        foreach ($data as $item) {
//            BackupMediaJob::dispatch($item);
//        }
//
//        dd('success');

//        BackupMediaJob::dispatch($data);


//        $awsDisk = Storage::disk('spaces');
//        $data = $disk->allFiles('storage');
//
//        $newArr = array_map(function ($item) {
//            return [
//                'file' => $item,
//                'path' => substr($item, strlen('storage/')),
//                'status' => 0,
//            ];
//        }, $data);
//
//        foreach ($newArr as $item)
//        {
//            BackupMediaDbJob::dispatch($item);
//        }
//
//        dd('success');


//        $path = 'istockphoto-1436225779-2048x2048-600x421.jpg';

//        $file = $disk->get('storage/istockphoto-1436225779-2048x2048-600x421.jpg');
//
//        $awsDisk->put($path, $file);
//        dd('success');


    }
}
