<?php

namespace App\Admin\Subcategory\Controllers;

use App\Http\Controllers\Controller;
use App\Admin\Categories\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Admin\Subcategory\Models\Subcategory;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class SubcategoryController extends Controller
{
    public function index()
    {
        $categories  = Category::get();
        return view('subcategory::index', compact('categories'));
    }

    public function edit($id = '')
    {
        if (!empty($id)) {
            $data = Subcategory::findOrFail($id);
            return response()->json($data);
        }
    }

    public function delete($id)
    {
        try {
            $data = Subcategory::findOrFail($id);
            $data->delete();
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
                'name'   => ['required', 'unique:' . Subcategory::class . ',name'],
                'image'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $name             = $request->input('name');
            $slug             = strtolower(str_replace(' ', '', $name));
            $category_id      = $request->input('category');
            $category_name    = Category::where('id', $category_id)->pluck('name');
            
            $description      = $request->input('description');
            $fullPath         = '../../';

            $subCategory                     = new Subcategory();
            $subCategory->name               = $name;
            $subCategory->slug               = $slug;
            $subCategory->category_id        = $category_id;
            $subCategory->category_name      = $category_name;
            
            $subCategory->description        = $description;
            $subCategory->image              = $fullPath;
            $subCategory->save();

            $id = $subCategory->id;

            $imageName = $slug . '_' . $id . '.' . $request->image->extension();
            $imagePath = 'images/subcategories/' . $id . '/';
            $fullPath  = $imagePath . $imageName;

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0777, true);
            }
            $image = $request->file('image');

            SubcategoryController::uploadResizedImages($image, $imageName, $imagePath);
            $subCategory        = Subcategory::findOrFail($id);
            $subCategory->image = '../../' . $fullPath;
            $subCategory->save();
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

    public static function uploadResizedImages($image, $imageName, $imagePath)
    {

        $img = Image::read($image->path());
        $sizes = [100, 200, 300];
        foreach ($sizes as $size) {
            $img->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imagePath . '/' . $size . '_' . $size . '_' . $imageName);
        }
        return true;
    }

    public function update(Request $request)
    {
        try {
            $id               = $request->input('id');
            $name             = $request->input('editName');
            $slug             = strtolower(str_replace(' ', '', $name));
            $category_id      = $request->input('editCategory');
            
            $category_name    = Category::where('id', $category_id)->pluck('name');
            
            $description      = $request->input('editDescription');
            $fullPath         = '../../';
            $currentImage     = $request->input('currentImage');
            // validate name field except current id
            $request->validate([
                'id'          => 'required',
                'editName' => [
                    'required',
                    'string',
                    Rule::unique('subcategories', 'name')->ignore($id),
                ],
                'editCategory'  => 'required',

            ]);

            if (!empty($request->editImage)) {
                $request->validate([
                    'editImage'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                $imageName = $slug . '_' . $id . '.' . $request->editImage->extension();
                $imagePath = 'images/subcategories/' . $id . '/';
                $fullPath  = $imagePath . $imageName;
                $request->editImage->move(public_path($imagePath), $imageName);
                $fullPath    = '../../' . $fullPath;
            } else {
                $fullPath    = $currentImage;
            }

            $subCategory                     = Subcategory::find($id);
            $subCategory->name               = $name;
            $subCategory->slug               = $slug;
            $subCategory->description        = $description;
            $subCategory->image              = $fullPath;
            $subCategory->save();
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

    public function getDetails(Request $request)
    {
        $columns = ['id', 'name', 'category_name', 'description', 'image'];
        $limit   = $request->input('length', 10);
        $start   = $request->input('start', 0);
        $search  = $request->input('search')['value'];

        $query = Subcategory::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('category_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');

        if ($orderColumnIndex !== null) {
            $orderColumn = $columns[$orderColumnIndex];
            $query->orderBy($orderColumn, $orderDirection);
        }


        $subcategories = $query->skip($start)->take($limit)->get();


        $data = $subcategories->map(function ($data) {
            $editAction    = '';
            $deleteAction  = '';
            if (Auth::user()->can('subcategory.edit') || Auth::user()->hasRole('superadmin')) {
                $editAction = '<a href="#" class="btn text-dark" data-id="' . $data->id . '" onclick="editData(' . $data->id . ')"><i class="fa-solid fa-pen-to-square"></i></a>';
            }
            if (Auth::user()->can('subcategory.delete') || Auth::user()->hasRole('superadmin')) {
                $deleteAction = '<a href="#" class="btn text-dark" data-id="' . $data->id . '" onclick="deleteData(' . $data->id . ')"><i class="fa-solid fa-trash"></i></a>';
            }
            return [
                'id' => $data->id,
                'name' => $data->name,
                'category_name' => $data->category_name,
                'description' => $data->description,
                'image' => '<img src="' . Storage::url($data->image) . '" width="100" height="100">',
                'action' => $editAction . $deleteAction,
            ];
        });

        $totalRecords = Subcategory::count();
        $filteredRecords = $query->count();

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }
}
