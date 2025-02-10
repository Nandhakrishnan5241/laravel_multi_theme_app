<?php

namespace App\Admin\CategorySubcategory\Controllers;

use App\Admin\Categories\Models\Category;
use App\Admin\CategorySubcategory\Models\CategorySubCategory;
use App\Admin\Subcategory\Models\Subcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;

class CategoySubCategoryController extends Controller
{
    public function index()
    {
        $categories    = CategorySubCategory::where('level_name', 'category')->get();
        $subCategories = CategorySubCategory::where('level_name', 'subcategory')->get();

        return view('categorysubcategory::index',compact('categories','subCategories'));
    }


    public function edit($id='')
    {
        if(!empty($id)){
            $data = CategorySubCategory::findOrFail($id);
            return response()->json($data);
        }
    }

    public function delete($id) {
        

        try {
            $data = CategorySubCategory::findOrFail($id);
            $data->delete();
            return response()->json(['status' => 200, 'success' => 'Data Deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $categoryName        = $request->input('categoryName');
            $subCategoryName     = $request->input('subCategoryName');
            $name                = ($categoryName ? $categoryName : $subCategoryName);
            $slug                = strtolower(str_replace(' ', '', $name));
            $categoryId          = $request->input('category');
            $subcategoryId       = $request->input('subCategory');
            $description         = $request->input('description');
            $fullPath            = '../../';

            $request->validate([
                'category'        => 'required',
                // 'name'        => ['required', 'unique:' . CategorySubCategory::class . ',name'],
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if($categoryId == 'c_add'){
                $parent_id      = Null;
                $level          = 0;
                $level_name     = 'category';
            }else{
                $parent_id      = $categoryId;
                $level          = 1;
                $level_name     = 'subcategory';
            }
            
            $data                = new CategorySubCategory();
            $data->name          = $name;
            $data->slug          = $slug;
            $data->description   = $description;
            $data->image         = $fullPath;
            $data->parent_id     = $parent_id;
            $data->level         = $level;
            $data->level_name    = $level_name;
            $data->save();

            $imageName = time() . '.' . $request->image->extension();
            if($categoryName){
                $imagePath = 'images/category/';
                $fullPath  = $imagePath . $imageName;
            }else{
                $imagePath = 'images/subcategory/';
                $fullPath  = $imagePath . $imageName;
            }

            $id = $data->id;

            $imageName = $slug. '_' . $id . '.' . $request->image->extension();
            $imagePath = $imagePath . $id .'/';
            $fullPath  = $imagePath . $imageName;

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0777, true);
            }
            $image = $request->file('image');

            
            
            CategoySubCategoryController::uploadResizedImages($image,$imageName,$imagePath);
            $product        = CategorySubCategory::findOrFail($id);
            $product->image = '../../' . $fullPath;
            $product->save();
            $request->image->move(public_path($imagePath), $imageName);

            return response()->json([
                'status' => '1',
                'message' => 'Data Saved Successfully...',
                'data' => [],
            ]);

        } catch (ValidationException $e) {
            $errors      = $e->validator->errors();
            $allMessages = $errors->all();
            return response()->json([
                'status' => '0',
                'message' => $allMessages[0],
                'data' => [],
            ]);
        }       
    }

    public static function uploadResizedImages($image,$imageName,$imagePath){
        
        $img = Image::read($image->path());
        $sizes = [100,200,300];
        foreach ($sizes as $size) {
            $img->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imagePath.'/'. $size.'_'.$size.'_'.$imageName);
        }
        return true;
        
    }

    public function update(Request $request) {
        try {
            $id                  = $request->input('id');
            $categoryName        = $request->input('editCategoryName');
            $subCategoryName     = $request->input('editSubCategoryName');
            $name                = ($categoryName ? $categoryName : $subCategoryName);
            $slug                = strtolower(str_replace(' ', '', $name));
            $categoryId          = $request->input('editCategory');
            $subcategoryId       = $request->input('editSubCategory');
            $description         = $request->input('description');
            $currentImage        = $request->input('currentImage');
            $fullPath            = '../../';
dd($name);
            if (!empty($request->editImage)) {
                $request->validate([
                    'editImage'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                $imageName = $slug. '_' . $id . '.' . $request->editImage->extension();
                if($categoryName){
                    $imagePath = 'images/category/';
                    $fullPath  = $imagePath . $imageName;
                }else{
                    $imagePath = 'images/subcategory/';
                    $fullPath  = $imagePath . $imageName;
                }
            } else {
                $fullPath    = $currentImage;
            }
            

            if($categoryId == 'c_add'){
                $parent_id      = Null;
                $level          = 0;
                $level_name     = 'category';
            }else{
                $parent_id      = $categoryId;
                $level          = 1;
                $level_name     = 'subcategory';
            }

            $data                = CategorySubCategory::find($id);
            $data->name          = $name;
            $data->slug          = $slug;
            $data->description   = $description;
            $data->image         = $fullPath;
            $data->parent_id     = $parent_id;
            $data->level         = $level;
            $data->level_name    = $level_name;
            $data->save();
            
            $data->save();
            return response()->json([
                'status' => '1',
                'message' => 'Data updated Successfully...',
                'data' => [],
            ]);
        } catch (ValidationException $e) {
            $errors      = $e->validator->errors();
            $allMessages = $errors->all();
            return response()->json([
                'status' => '0',
                'message' => $allMessages[0],
                'data' => [],
            ]);
        }
    }

    public function getDetails(Request $request){

        $columns = ['id', 'name','level_name', 'description', 'image']; 
        $limit = $request->input('length', 10); 
        $start = $request->input('start', 0); 
        $search = $request->input('search')['value']; 

        $query = CategorySubCategory::query();
       
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $orderColumnIndex = $request->input('order.0.column'); 
        $orderDirection = $request->input('order.0.dir'); 

        if ($orderColumnIndex !== null) {
            $orderColumn = $columns[$orderColumnIndex];
            $query->orderBy($orderColumn, $orderDirection);
        }


        $tableData = $query->skip($start)->take($limit)->get();

    
        $data = $tableData->map(function ($category) {
            $editAction    = '';
            $deleteAction  = '';
            if (Auth::user()->can('categorysubcategory.edit') || Auth::user()->hasRole('superadmin')) {
                $editAction = '<a href="#" class="btn text-dark" data-id="' . $category->id . '" onclick="editData(' . $category->id . ')"><i class="fa-solid fa-pen-to-square"></i></a>';
            }
            if (Auth::user()->can('categorysubcategory.delete') || Auth::user()->hasRole('superadmin')) {
                $deleteAction = '<a href="#" class="btn text-dark" data-id="' . $category->id . '" onclick="deleteData(' . $category->id . ')"><i class="fa-solid fa-trash"></i></a>';
            }
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'level_name' => $category->level_name,
                'image' => '<img src="' . Storage::url($category->image) . '" width="100" height="100">',
                'action' => $editAction . $deleteAction,
            ];
        });

        $totalRecords = CategorySubCategory::count();
        $filteredRecords = $query->count();

        return response()->json([
            'draw' => $request->input('draw'), 
            'recordsTotal' => $totalRecords, 
            'recordsFiltered' => $filteredRecords, 
            'data' => $data,
        ]);
    }
}
