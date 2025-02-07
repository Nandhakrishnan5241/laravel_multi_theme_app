<?php

namespace App\Admin\Categories\Controllers;

use App\Admin\Categories\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories::index');
    }


    public function edit($id='')
    {
        if(!empty($id)){
            $category = Category::findOrFail($id);
            return response()->json($category);
        }
    }

    public function delete($id) {
        

        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['status' => 200, 'success' => 'Data Deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => 'Data Deleted Successfully...'
        ]);
    }

    public function save(Request $request)
    {
        try {
            $request->validate([
                'name'        => ['required', 'unique:' . Category::class . ',name'],
                'description' => 'required',
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imageName = time() . '.' . $request->image->extension();
            $imagePath = 'images/category/';
            $fullPath  = $imagePath . $imageName;

            $request->image->move(public_path($imagePath), $imageName);

            $name          = $request->input('name');
            $description   = $request->input('description');
            $fullPath      = '../../'.$fullPath;


            $category                = new Category();
            $category->name          = $name;
            $category->description   = $description;
            $category->image         = $fullPath;
            $category->save();

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

    public function update(Request $request) {
        try {
            $id            = $request->input('id');
            $name          = $request->input('editName');
            $description   = $request->input('editDescription');
            $currentImage  = $request->input('currentImage');

            $request->validate([
                'id'          => 'required',
                'editName' => [
                    'required',
                    'string',
                    Rule::unique('categories', 'name')
                        ->where('id', $id)
                        ->ignore($id),
                ],
                'editDescription' => 'required',
            ]);

            if (!empty($request->editImage)) {
                $request->validate([
                    'editImage'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $imageName = time() . '.' . $request->editImage->extension();
                $imagePath = 'images/category/';
                $fullPath  = $imagePath . $imageName;
                $request->editImage->move(public_path($imagePath), $imageName);
                $fullPath    = '../../' . $fullPath;
            } else {
                $fullPath    = $currentImage;
            }

            $category                = Category::find($id);
            $category->name          = $name;
            $category->description   = $description;
            $category->image         = $fullPath;
            $category->save();

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

    public function imageUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048'
        ]);
        $imageName = time() . '.' . $request->image->extension();
        $image = $request->file('image');
        $fileName = $image->getClientOriginalName();
        $request->image->move(public_path('images'), $imageName);


        return back()->with('success', "Image Uploaded successfully...")->with('image', $imageName);
    }

    public function getDetails(Request $request){

        $columns = ['id', 'name', 'description', 'image']; 
        $limit = $request->input('length', 10); 
        $start = $request->input('start', 0); 
        $search = $request->input('search')['value']; 

        $query = Category::query();
       
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


        $categories = $query->skip($start)->take($limit)->get();

    
        $data = $categories->map(function ($category) {
            $editAction    = '';
            $deleteAction  = '';
            if (Auth::user()->can('categories.edit') || Auth::user()->hasRole('superadmin')) {
                $editAction = '<a href="#" class="btn text-dark" data-id="' . $category->id . '" onclick="editData(' . $category->id . ')"><i class="fa-solid fa-pen-to-square"></i></a>';
            }
            if (Auth::user()->can('categories.delete') || Auth::user()->hasRole('superadmin')) {
                $deleteAction = '<a href="#" class="btn text-dark" data-id="' . $category->id . '" onclick="deleteData(' . $category->id . ')"><i class="fa-solid fa-trash"></i></a>';
            }
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => '<img src="' . Storage::url($category->image) . '" width="100" height="100">',
                'action' => $editAction . $deleteAction,
            ];
        });

        $totalRecords = Category::count();
        $filteredRecords = $query->count();

        return response()->json([
            'draw' => $request->input('draw'), 
            'recordsTotal' => $totalRecords, 
            'recordsFiltered' => $filteredRecords, 
            'data' => $data,
        ]);
    }
}
