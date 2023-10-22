<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;

class TempImagesController extends Controller
{
    public function Create(Request $request){
        $image = $request->image;

        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            $tempImage = new TempImage();
            $tempImage->imageName = $newName;
            $tempImage->save();

            $image->move(public_path().'/tempimages',$newName);

        return response()->json([
            'success' => true,
            'image_id' => $tempImage->id,
            'message' =>'Image Upload Successfully'
        ]);
    }
}

}