@extends('layouts.partials.admin')


@section('title', 'Manage Users')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
<style>
    .validation,
    .validation:focus {
        color: red !important;
        border-color: red !important;
    }

    .validation::placeholder {
        color: red !important;
        opacity: 1 !important;
    }
</style>
@vite(['resources/js/users.js'])

@section('content')
    <div class="container-fluid">
        <div class="mt-4 h4">Manage Users
            @if (auth()->user()->can('users.create') || auth()->user()->hasRole('superadmin'))
                <button class="btn btn-primary float-end me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Add User</button>
            @endif
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col-4">
                @if (auth()->user()->hasRole('superadmin'))
                    <label for="client">Client</label>
                    <select class="form-select" name="client" id="client" onchange="getSelectedClient(this)">
                        <option value="" disabled selected>Select a client</option>
                        @foreach ($clients as $key => $value)
                            <option value="{{ $value['id'] }}">{{ $value['company_name'] }}</option>
                        @endforeach
                    </select>
                    <div id="client-error" class="mx-2" style="color: red; display: none;">Please select a client</div>
                @endif
            </div>


            {{-- <div class="table-responsive"> --}}
            <div class="row mt-4">
                <table id="usersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD --}}

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel"
        style="width: 50%">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Create User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="addUserForm" action="{{ route('users.save') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" name="name" type="text" placeholder="Enter the name" id="name" />
                </div>
                <div class="mb-3">
                    <label for="displayName">Display Name</label>
                    <input class="form-control" name="displayName" type="text" placeholder="Enter the displayName"
                        id="displayName" />
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" name="email" type="email" placeholder="Enter the name" id="email" />
                </div>
                <div class="mb-3">
                    @if (auth()->user()->hasRole('superadmin'))
                        <label for="client_id">Client</label>
                        <select class="form-select" name="client_id" id="client_id">
                            <option value="" disabled selected>Select a client</option>
                            @foreach ($clients as $key => $value)
                                <option value="{{ $value['id'] }}">{{ $value['company_name'] }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="role">Role</label>
                    <select class="form-select" name="role[]" id="role" multiple>
                        <option value="" disabled selected>Select a role</option>
                        @foreach ($roles as $key => $value)
                            <option value="{{ $value['name'] }}">{{ $value['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required />
                </div>


                <!-- Hidden fields for phone number and country code -->
                <input type="hidden" id="phone_number" name="phone_number">
                <input type="hidden" id="country_code" name="country_code">

                <div class="mt-4 mb-0">
                    <button class="btn btn-primary float-end" type="submit">Save</button>
                </div>
            </form>

        </div>
    </div>

    {{-- EDIT OFFCANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editOffCanvasRight" aria-labelledby="editOffcanvasRightLabel"
        style="width: 50%">
        <div class="offcanvas-header">
            <h5 id="editOffcanvasRightLabel">Update User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="editUserForm" action="{{ route('users.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input name="id" type="hidden" placeholder="" id="id" />

                    <label for="name">Name</label>
                    <input class="form-control" name="editName" type="text" placeholder="Enter the name"
                        id="editName" />
                </div>
                <div class="mb-3">
                    <label for="editDisplayName">Display Name</label>
                    <input class="form-control" name="editDisplayName" type="text"
                        placeholder="Enter the editDisplayName" id="editDisplayName" />
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" name="editEmail" type="email" placeholder="Enter the name"
                        id="editEmail" />
                </div>
                {{-- <div class="mb-3">
                    @if (auth()->user()->hasRole('superadmin'))
                        <label for="edit_client_id">Client</label>
                        <select class="form-select" name="edit_client_id" id="edit_client_id">
                            <option value="" disabled selected>Select a client</option>
                            @foreach ($clients as $key => $value)
                                <option value="{{ $value['id'] }}">{{ $value['company_name'] }}</option>
                            @endforeach
                        </select>
                    @endif
                </div> --}}
                <div class="mb-3">
                    <label for="editRole">Role</label>
                    <select class="form-select" name="editRole[]" id="editRole" multiple>
                        <option value="" disabled selected>Select a role</option>
                        @foreach ($roles as $key => $value)
                            <option value="{{ $value['name'] }}">{{ $value['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editPhone">Mobile</label>
                    <input class="form-control" name="editPhone" type="tel" placeholder="Enter the mobile"
                        id="editPhone" />
                </div>

                <input type="hidden" id="edit_phone_number" name="edit_phone_number">
                <input type="hidden" id="edit_country_code" name="edit_country_code">

                <div class="mt-4 mb-0">
                    <button class="btn btn-primary float-end" type="submit">Save</button>
                </div>
            </form>

        </div>
    </div>

    {{-- CHANGE PASSWORD OFFCANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="changePasswordCanvas"
        aria-labelledby="changePasswordCanvasLabel" style="width: 50%">
        <div class="offcanvas-header">
            <h5 id="changePasswordCanvasLabel">Update User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="userChangePasswordForm" action="{{ route('users.changepassword') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-10 position-relative">
                        <label for="userpassword">New Password</label>
                        <input class="form-control" name="userpassword" type="password" id="userpassword"
                            placeholder="Enter the new password" />
                        <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y my-3 mx-2"
                            onclick="togglePasswordVisibility()">
                            <i id="toggleIcon" class="fa-solid fa-eye bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="col-2 mt-4">
                        <button class="btn btn-secondary float-start" type="button"
                            onclick="generatePassword()">Generate</button>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-primary float-end" type="submit">Save</button>
                    </div>
                </div>


            </form>

        </div>
    </div>

@endsection
