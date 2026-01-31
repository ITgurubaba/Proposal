<?php

namespace App\Http\Controllers\Admin\Api;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class   FileUploadController extends Controller
{
    public $allowedExtensions = [];

    public function __construct()
    {
        $this->allowedExtensions = config('app.allowed_file_extension');
    }

    public function UploadFile(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'pic'=>'required'
        ]);

        if(!$validator->fails())
        {
            $folder =  $request->header('folder')?$request->header('folder'):"images/";
            $file = $request->file('pic');
            $extension = $file->getClientOriginalExtension();
            if(in_array(strtolower($extension), $this->allowedExtensions, true))
            {
              $media =  FileUploadHelper::StoreMedia($file,$folder,config('settings.media_disk','public_uploads'));
              return response()->json([
                  'success'=>true,
                  'data'=>$media,
              ]);
            }

        }
        return response()->json([
            'success'=>false,
            'message'=>'Invalid File',
        ],400);
    }

    public function RevertFile(Request $request)
    {
        $data = FileUploadHelper::revert_media($request->post('file_id'));
        return response()->json([
            'success'=>true,
            'message'=>'File Removed',
            'data'=>$data
        ]);
    }

}
