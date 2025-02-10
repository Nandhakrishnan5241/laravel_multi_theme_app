@extends('layouts.partials.admin')
@section('title', 'Manage Products')
<style>
    .image-preview {
        max-width: 300px;
        max-height: 300px;
        margin-top: 20px;
    }

    .image-container {
        display: none;
    }
</style>

@vite(['resources/js/categorysubcategory.js'])
@section('content')
    <div class="container-fluid">
        <div class="mt-4 h4">Manage Category Subcatergory
            @if (auth()->user()->can('categorysubcategory.create') || auth()->user()->hasRole('superadmin'))
                <button class="btn btn-primary float-end me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#addOffcanvasRight" aria-controls="addOffcanvasRight">Add New</button>
            @endif
        </div>
        <hr>
        <table id="tableData" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Level</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated by DataTables -->
            </tbody>
        </table>
    </div>
    {{-- ADD --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addOffcanvasRight" aria-labelledby="addOffcanvasRightLabel"
        style="width: 50%">
        <div class="offcanvas-header">
            <h5 id="addOffcanvasRightLabel">Create Category</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="addForm" action="{{ route('categorysubcategory.save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="categoryN">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="" disabled selected>Select a category</option>
                            <option value="c_add">add new category</option>
                            @foreach ($categories as $key => $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-6 mb-3" id="new-category-div" style="display: none;">
                        <label for="categoryName">Category Name</label>
                        <input class="form-control" name="categoryName" type="text"
                            placeholder="Enter the category name"id="categoryName" />
                    </div>
                    <div class="col-6 mb-3" id="subCategoryDiv" style="display: block;">
                        <label for="subCategory">Sub Category</label>
                        <select class="form-select" name="subCategory" id="subCategory">
                            <option value="" disabled selected>Select a sub category</option>
                            <option value="sc_add">add new sub category</option>
                            @foreach ($subCategories as $key => $subCategory)
                                <option value="{{ $subCategory['id'] }}">{{ $subCategory['name'] }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-6 mb-3" id="new-sub-category-div" style="display: none;">
                        <label for="subCategoryName">Sub Category Name</label>
                        <input class="form-control" name="subCategoryName" type="text"
                            placeholder="Enter the sub category name"id="subCategoryName" />
                    </div>
                    <div class="col-6 mb-3">
                        <label for="description">Description</label>
                        <input class="form-control" name="description" type="text"
                            placeholder="Enter the product description"id="description" />
                    </div>
                    <div class="col-6 mb-3">
                        <label for="image">Image</label>
                        <input class="form-control" name="image" type="file" id="image" />
                        <!-- Image Preview -->
                        <div class="image-container">
                            <img id="imagePreview" class="image-preview" src="{{ asset('') }}" alt="Image Preview">
                        </div>
                    </div>
                    <div class="mt-4 mb-0">
                        <button class="btn btn-primary float-end" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

     {{-- EDIT --}}
     <div class="offcanvas offcanvas-end" tabindex="-1" id="editOffCanvasRight" aria-labelledby="editOffcanvasRightLabel"
     style="width: 50%">
     <div class="offcanvas-header">
         <h5 id="editOffcanvasRightLabel">Update Product</h5>
         <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
     </div>
     <div class="offcanvas-body">
        <form id="editForm" action="{{ route('categorysubcategory.update') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="category">Category</label>
                    <select class="form-select" name="editCategory" id="editCategory">
                        <option value="" disabled selected>Select a category</option>
                        <option value="c_add">add new category</option>
                        @foreach ($categories as $key => $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-6 mb-3" id="editCategoryNameDiv" style="display: none;">
                    <label for="editCategoryName">Category Name</label>
                    <input class="form-control" name="editCategoryName" type="text"
                        placeholder="Enter the category name"id="editCategoryName" />
                </div>
                <div class="col-6 mb-3" id="editSubCategoryDiv" style="display: none;">
                    <label for="subCategory">Sub Category</label>
                    <select class="form-select" name="editSubCategory" id="editSubCategory">
                        <option value="" disabled selected>Select a sub category</option>
                        <option value="sc_add">add new sub category</option>
                        @foreach ($subCategories as $key => $subCategory)
                            <option value="{{ $subCategory['id'] }}">{{ $subCategory['name'] }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-6 mb-3" id="editSubCategoryNameDiv" style="display: none;">
                    <label for="editSubCategoryName">Sub Category Name</label>
                    <input class="form-control" name="editSubCategoryName" type="text"
                        placeholder="Enter the sub category name"id="editSubCategoryName" />
                </div>
                <div class="col-6 mb-3">
                    <label for="editDescription">Description</label>
                    <input class="form-control" name="editDescription" type="text"
                        placeholder="Enter the product description"id="editDescription" />
                </div>
                <div class="col-6 mb-3">
                    <label for="editImage">Image</label>
                    <input class="form-control" name="editImage" type="file" id="editImage" />
                    <!-- Image Preview -->
                    <div class="image-container">
                        <img id="editImagePreview" class="image-preview" src="{{ asset('') }}" alt="Image Preview">
                    </div>
                </div>
                <div class="mt-4 mb-0">
                    <button class="btn btn-primary float-end" type="submit">Update</button>
                </div>
            </div>
        </form>
     </div>
 </div>
@endsection
