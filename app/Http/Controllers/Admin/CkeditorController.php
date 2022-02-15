<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CkeditorController extends Controller
{
    /***
     * @param Request $request
     */
    public function upload(Request $request)
    {
//        if($request->hasFile('upload')) {
//            $originName = $request->file('upload')->getClientOriginalName();
//            $fileName = pathinfo($originName, PATHINFO_FILENAME);
//            $extension = $request->file('upload')->getClientOriginalExtension();
//            $fileName = $fileName.'_'.time().'.'.$extension;
//
//            $request->file('upload')->move(public_path().'/storage/information', $fileName);
//
//            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
//            $url = asset('storage/information/'.$fileName);
//            dd(['filename' => $fileName, 'success' => $request->file('upload')->move(public_path().'/storage/information', $fileName)]);
//            $msg = 'Image uploaded successfully';
//            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
//
//            @header('Content-type: text/html; charset=utf-8');
//            echo $response;
//        }

//        if ($request->hasFile('upload')) {
//            //get filename with extension
//            $filenamewithextension = $request->file('upload')->getClientOriginalName();
//
//            //get filename without extension
//            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
//
//            //get file extension
//            $extension = $request->file('upload')->getClientOriginalExtension();
//
//            //filename to store
//            $filenametostore = $filename . '_' . time() . '.' . $extension;
//
//            //Upload File
//            dd(['filename' => $filenametostore, 'success' => $request->file('upload')->storeAs('public/information', $filenametostore)]);
//
//            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
//            $url = asset('storage/information/' . $filenametostore);
//            $msg = 'Image successfully uploaded';
//            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
//
//            // Render HTML output
//            @header('Content-type: text/html; charset=utf-8');
//            echo $re;
//        }

        $file = $request->upload;
        $fileName = $file->getClientOriginalName();
        $newName = time().$fileName;
        $dir = "storage/files/";
        $file->move($dir, $newName);
        $url = asset('storage/files/'.$newName);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $status = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', 'Image successfully uploaded')</script>";
        echo $status;

    }
}
