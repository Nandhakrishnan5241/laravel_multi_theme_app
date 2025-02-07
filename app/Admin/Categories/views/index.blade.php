@extends('layouts.partials.admin')

@section('title', 'Manage Category')

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
@vite(['resources/js/category.js'])
@section('content')
    <div class="container-fluid">

        <div class="mt-4 h4">Manage Category
            @if (auth()->user()->can('categories.create') || auth()->user()->hasRole('superadmin'))
                <button class="btn btn-primary float-end me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#addOffcanvasRight" aria-controls="addOffcanvasRight">Add Category</button>
            @endif
        </div>
        <hr>

        
        <table id="categoryTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
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
    
    {{-- ADD OFFCANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addOffcanvasRight" aria-labelledby="addOffcanvasRightLabel"
        style="width: 50%">
        <div class="offcanvas-header">
            <h5 id="addOffcanvasRightLabel">Create Category</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="addCategoryForm" action="{{ route('categories.save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="name">Category Name</label>
                        <input class="form-control" name="name" type="text"
                            placeholder="Enter the category name"id="name" />
                    </div>
                    <div class="col-6 mb-3">
                        <label for="description">Category Description</label>
                        <input class="form-control" name="description" type="text"
                            placeholder="Enter the Category description"id="description" />
                    </div>
                    <div class="col-6 mb-3">
                        <label for="image">Category Image</label>
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
    {{-- EDIT OFFCANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editOffCanvasRight" aria-labelledby="editOffcanvasRightLabel"
        style="width: 50%">
        <div class="offcanvas-header">
            <h5 id="editOffcanvasRightLabel">Update Category</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="editCategoryForm" action="{{ route('categories.update') }}" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" id="id" name="id">
                    <input class="form-control" name="currentImage" id="currentImage" type="hidden"
                        placeholder="" />
                    <div class="col-6 mb-3">
                        <label for="editName">Category Name</label>
                        <input class="form-control" name="editName" type="text"
                            placeholder="Enter the category name"id="editName" />
                    </div>
                    
                    <div class="col-6 mb-3">
                        <label for="editDescription">Category Description</label>
                        <input class="form-control" name="editDescription" type="text"
                            placeholder="Enter the category description"id="editDescription" />
                    </div>
                    <div class="col-6 mb-3">
                        <label for="editImage">Category Image</label>
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
