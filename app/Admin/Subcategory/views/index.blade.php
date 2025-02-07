@extends('layouts.partials.admin')
@section('title', 'Manage Subcategory')
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


@vite(['resources/js/subcategory.js'])
@section('content')
    <div class="container-fluid">
        <div class="mt-4 h4">Manage SubCategory
            @if (auth()->user()->can('subcategory.create') || auth()->user()->hasRole('superadmin'))
                <button class="btn btn-primary float-end me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#addOffcanvasRight" aria-controls="addOffcanvasRight">Add Subcategory</button>
            @endif
        </div>
        <hr>
        <table id="subCategoryTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
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
            <h5 id="addOffcanvasRightLabel">Create Subcategory</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="addCategoryForm" action="{{ route('subcategory.save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="name">Name</label>
                        <input class="form-control" name="name" type="text"
                            placeholder="Enter the product name"id="name" />
                    </div>
                    <div class="col-6 mb-3">
                        <label for="category">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="" disabled selected>Select a category</option>
                            @foreach ($categories as $key => $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="image">Image</label>
                        <input class="form-control" name="image" type="file" id="image" />
                        <!-- Image Preview -->
                        <div class="image-container">
                            <img id="imagePreview" class="image-preview" src="{{ asset('') }}" alt="Image Preview">
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="description">Description</label>
                        <input class="form-control" name="description" type="text"
                            placeholder="Enter the product description"id="description" />
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
         <form id="editCategoryForm" action="{{ route('subcategory.update') }}" method="POST">
             @csrf
             <div class="row">
                 <input type="hidden" id="id" name="id">
                 <input class="form-control" name="currentImage" id="currentImage" type="hidden"
                     placeholder="" />
                 <div class="col-6 mb-3">
                     <label for="editName">Name</label>
                     <input class="form-control" name="editName" type="text"
                         placeholder="Enter the product name"id="editName" />
                 </div>
                 <div class="col-6 mb-3">
                     <label for="editCategory">Product Category</label>
                     <select class="form-select" name="editCategory" id="editCategory">
                         <option value="" disabled selected>Select a category</option>
                         @foreach ($categories as $key => $category)
                             <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                         @endforeach

                     </select>
                 </div>
                 <div class="col-6 mb-3">
                     <label for="editImage">Product Image</label>
                     <input class="form-control" name="editImage" type="file" id="editImage" />
                     <!-- Image Preview -->
                     <div class="image-container">
                         <img id="editImagePreview" class="image-preview" src="{{ asset('') }}" alt="Image Preview">
                     </div>
                 </div>
                 <div class="col-6 mb-3">
                    <label for="editDescription">Product Description</label>
                    <input class="form-control" name="editDescription" type="text"
                        placeholder="Enter the product description"id="editDescription" />
                </div>
                <div class="mt-4 mb-0">
                    <button class="btn btn-primary float-end" type="submit">Update</button>
                </div>
             </div>
         </form>
     </div>
 </div>
@endsection
