<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CategoryModel;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;

class CategoryController extends Controller
{
    public function CategoryList(Request $request)
    {

        $category = CategoryModel::latest();

        if(!empty($request->get('keyword'))){
            $category = $category->where('name','like','%'.$request->get('keyword').'%');
        }
        $category = $category->paginate(5);

        return view('admin.category.list', compact('category'));
    }


    public function Create()
    {
        return view ('admin.category.create');
    }


     public function Store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()){

            $category = new CategoryModel();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            //save image here

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->imageName);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $spath = public_path().'/tempimages/'.$tempImage->imageName;
                $dpath = public_path().'\uplodes\category/'.$newImageName;
                File::copy($spath,$dpath);

                //Generate Image Thumbnail
                $dpath = public_path().'\uplodes\category/thumb/'.$newImageName;
                $img = Image::make($spath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dpath);

                $category->image = $newImageName;
                $category->save();
            }
            
           $request->session()->flash('success', 'Category successfully created');


             return response()->json(['success' =>true,
             'message' => 'Category successfully created'
            ]);
            
        }else{
            return response()->json(['success' =>false,
             'errors' =>$validator->errors()
            ]);
        }
        
    }


     public function Edit($id)
    {

        
        $category = CategoryModel::find($id);
        if (empty($category)){
            return redirect()->route('category.list');
        }

       return view('admin.category.edit',compact('category'));
    
    }
    

     public function Update(Request $request, $id)
    {

         $category = CategoryModel::find($id);

        if (empty($category)){
            $request->session()->flash('error', 'Category Not Found');
            
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()){

         
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();
            
            $oldImage = $category->image;

            //save image here

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->imageName);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $spath = public_path().'/tempimages/'.$tempImage->imageName;
                $dpath = public_path().'\uplodes\category/'.$newImageName;
                File::copy($spath,$dpath);

                //Generate Image Thumbnail
                $dpath = public_path().'\uplodes\category/thumb/'.$newImageName;
                $img = Image::make($spath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dpath);

                $category->image = $newImageName;
                $category->save();

                //Delete Old Image
                File::delete(public_path().'\uplodes\category/thumb/'.$oldImage);
                File::delete(public_path().'\uplodes\category/'.$oldImage);
            }
            
           $request->session()->flash('success', 'Category successfully Updated');


             return response()->json(['success' =>true,
             'message' => 'Category successfully Updated'
            ]);
            
        }else{
            return response()->json(['success' =>false,
             'errors' =>$validator->errors()
            ]);
        }
    }

     public function Delete( Request $request,$id)
    {
         $category = CategoryModel::find($id);
        if (empty($category)){
            $request->session()->flash('error', 'Category Not Found');
            return response()->json(['success' =>true,
             'message' => 'Category Not Found'
            ]);

            // return redirect()->route('category.list');
        }

        File::delete(public_path().'\uplodes\category/thumb/'.$category->image);
        File::delete(public_path().'\uplodes\category/'.$category->image);

         $category->delete();

         $request->session()->flash('success', 'Category successfully Deleted');

         return response()->json(['success' =>true,
             'message' => 'Category successfully Deleted'
            ]);
    }
}