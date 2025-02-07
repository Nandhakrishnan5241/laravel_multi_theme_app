<?php

namespace App\Admin\Products\Controllers;

use App\Admin\Products\Models\Product;
use App\Admin\Categories\Models\Category;
use App\Admin\Subcategory\Models\Subcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;


class ProductsController extends Controller
{

    public function index()
    {
        $categories     = Category::get();
        $subCategories  = Subcategory::get();
        return view('products::index',compact('categories','subCategories'));
    }

    public function edit($id='')
    {
        if(!empty($id)){
            $product = Product::findOrFail($id);
            return response()->json($product);
        }
    }

    public function delete($id) {
        

        try {
            $product = Product::findOrFail($id);
            $product->delete();
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
                'name'   => ['required', 'unique:' . Product::class . ',name'],
                'price'      => 'required',
                'tax'        => 'required',
                'category'   => 'required',
                'stock'      => 'required',
                'image'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $name                = $request->input('name');
            $slug                = strtolower(str_replace(' ', '', $name));
            $category_id         = $request->input('category');
            $category_name       = Category::where('id', $category_id)->pluck('name');
            $subCategory_id      = $request->input('subCategory');
            $subCategory_name    = Subcategory::where('id', $subCategory_id)->pluck('name');
            $stock               = $request->input('stock');
            $price               = $request->input('price');
            $tax                 = $request->input('tax');
            $description         = $request->input('description');
            $fullPath            = '../../';

           

            $product                     = new Product();
            $product->name               = $name;
            $product->slug               = $slug;
            $product->category_id        = $category_id;
            $product->category_name      = $category_name;
            $product->sub_category_id    = $subCategory_id;
            $product->sub_category_name  = $subCategory_name;
            $product->available_stocks   = $stock;
            $product->price              = $price;
            $product->tax                = $tax;
            $product->description        = $description;
            $product->image              = $fullPath;
            $product->save();

            $product_id = $product->id;

            
            $imageName = $slug. '_' . $product_id . '.' . $request->image->extension();
            $imagePath = 'images/products/'. $product_id .'/';
            $fullPath  = $imagePath . $imageName;

            if (!file_exists($imagePath)) {
                mkdir($imagePath, 0777, true);
            }

            $image = $request->file('image');
            
            ProductsController::uploadResizedImages($image,$imageName,$imagePath);
            $product        = Product::findOrFail($product_id);
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
            $id            = $request->input('id');
            $name          = $request->input('editName');
            $slug          = strtolower(str_replace(' ', '', $name));
            $stock         = $request->input('editStock');
            $price         = $request->input('editPrice');
            $tax           = $request->input('editTax');
            $description   = $request->input('editDescription');
            $currentImage  = $request->input('currentImage');

            $category_id      = $request->input('editCategory');
            $category_name    = Category::where('id', $category_id)->pluck('name');
            $subCategory_id   = $request->input('editSubCategory');
            $subCategory_name = Subcategory::where('id', $subCategory_id)->pluck('name');
            // validate name field except current id
            $request->validate([
                'id'          => 'required',
                'editName' => [
                    'required',
                    'string',
                    Rule::unique('products', 'name')->ignore($id),
                ],
                'editPrice' => 'required',
                'editTax' => 'required',
                'editStock' => 'required',
            ]);

            if (!empty($request->editImage)) {
                $request->validate([
                    'editImage'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                $imageName = $slug. '_' . $id . '.' . $request->editImage->extension();
                $imagePath = 'images/products/'. $id .'/';
                $fullPath  = $imagePath . $imageName;
                $request->editImage->move(public_path($imagePath), $imageName);
                $fullPath    = '../../' . $fullPath;
            } else {
                $fullPath    = $currentImage;
            }

            $product                     = Product::find($id);
            $product->name               = $name;
            $product->available_stocks   = $stock;
            $product->price              = $price;
            $product->tax                = $tax;
            $product->description        = $description;
            $product->image              = $fullPath;

            $product->category_id        = $category_id;
            $product->category_name      = $category_name;
            $product->sub_category_id    = $subCategory_id;
            $product->sub_category_name  = $subCategory_name;
            
            $product->save();
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
        $columns = ['id', 'name','available_stocks','price','tax', 'description', 'image']; 
        $limit = $request->input('length', 10); 
        $start = $request->input('start', 0); 
        $search = $request->input('search')['value']; 

        $query = Product::query();
       
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


        $products = $query->skip($start)->take($limit)->get();

    
        $data = $products->map(function ($product) {
            $editAction    = '';
            $deleteAction  = '';
            if (Auth::user()->can('products.edit') || Auth::user()->hasRole('superadmin')) {
                $editAction = '<a href="#" class="btn text-dark" data-id="' . $product->id . '" onclick="editData(' . $product->id . ')"><i class="fa-solid fa-pen-to-square"></i></a>';
            }
            if (Auth::user()->can('products.delete') || Auth::user()->hasRole('superadmin')) {
                $deleteAction = '<a href="#" class="btn text-dark" data-id="' . $product->id . '" onclick="deleteData(' . $product->id . ')"><i class="fa-solid fa-trash"></i></a>';
            }
            return [
                'id' => $product->id,
                'name' => $product->name,
                'available_stocks' => $product->available_stocks,
                'price' => $product->price,
                'tax' => $product->tax,
                'description' => $product->description,
                'image' => '<img src="' . Storage::url($product->image) . '" width="100" height="100">',
                'action' => $editAction . $deleteAction,
            ];
        });

        $totalRecords = Product::count();
        $filteredRecords = $query->count();

        return response()->json([
            'draw' => $request->input('draw'), 
            'recordsTotal' => $totalRecords, 
            'recordsFiltered' => $filteredRecords, 
            'data' => $data,
        ]);
    }
}
