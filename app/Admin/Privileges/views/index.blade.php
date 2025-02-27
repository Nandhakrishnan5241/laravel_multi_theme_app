@extends('layouts.partials.admin')

@section('title', 'Manage Privileges')
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

@vite(['resources/js/privileges.js'])

@section('content')
    <div class="container-fluid">
        <div class="mt-4 h4">Manage Privileges</div>
        <hr>
        <div class="row mt-3">
            <div class="col-4">
                @if (auth()->user()->can('privileges.create') || auth()->user()->hasRole('superadmin'))
                    <label for="">Role</label>
                    <select class="form-select" name="role" id="role" onchange="updatePrivilegesByClientAndRoleID()">
                        <option value="0" disabled selected>Select a role</option>
                        @foreach ($roles as $key => $value)
                            <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                        @endforeach
                    </select>
                    <div id="role-error" class="mx-2" style="color: red; display: none;">Please select a role</div>
                @endif
            </div>
            <div class="col-4">
                @if (auth()->user()->hasRole('superadmin'))
                    <label for="">Client</label>
                    <select class="form-select" name="client" id="client"
                        onchange="updatePrivilegesByClientAndRoleID()">
                        <option value="" disabled selected>Select a client</option>
                        @foreach ($clients as $key => $value)
                            <option value="{{ $value['id'] }}">{{ $value['company_name'] }}</option>
                        @endforeach
                    </select>
                    <div id="client-error" class="mx-2" style="color: red; display: none;">Please select a client</div>
                @else
                    <input type="hidden" name="client" id="client" value="0">
                @endif
            </div>


            <div class="mt-3" id="privelegesTable" style="display: none;">
                <table id="privilegesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Module</th>
                            <th>All</th>
                            <th>Create</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            {{-- <th>Disable</th> --}}
                        </tr>
                    </thead>
                    <tbody id="privilegesTableBody">
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($modules as $index => $module)
                            <tr data-row="{{ $index }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $module['name'] }}</td>
                                <td><input type="checkbox" class="all-checkbox permission-checkbox" data-row="{{ $index }}"
                                        data-module="{{ $module }}" data-action="all"></td>
                                <td><input type="checkbox" class="row-checkbox permission-checkbox"
                                        data-row="{{ $index }}" data-module="{{ $module }}"
                                        data-action="create"
                                        {{ in_array($module['slug'] . '.create', $roleHasPermissions) ? 'checked' : '' }}>
                                </td>
                                <td><input type="checkbox" class="row-checkbox permission-checkbox"
                                        data-row="{{ $index }}" data-module="{{ $module }}"
                                        data-action="view"
                                        {{ in_array($module['slug'] . '.view', $roleHasPermissions) ? 'checked' : '' }}>
                                </td>
                                <td><input type="checkbox" class="row-checkbox permission-checkbox"
                                        data-row="{{ $index }}" data-module="{{ $module }}"
                                        data-action="edit"
                                        {{ in_array($module['slug'] . '.edit', $roleHasPermissions) ? 'checked' : '' }}>
                                </td>
                                <td><input type="checkbox" class="row-checkbox permission-checkbox"
                                        data-row="{{ $index }}" data-module="{{ $module }}"
                                        data-action="delete"
                                        {{ in_array($module['slug'] . '.delete', $roleHasPermissions) ? 'checked' : '' }}>
                                </td>
                                {{-- <td><input type="checkbox" class="disable-checkbox" data-row="{{ $index }}" data-action="disable"></td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                @if (auth()->user()->can('privileges.create') || auth()->user()->hasRole('superadmin'))
                    <button class="btn btn-primary mt-3 float-end" onclick="getSelectedValues()">Submit</button>
                @endif
            </div>
        </div>
    </div>
@endsection

<script>
    var modules = @json($modules);
    console.log(modules);
</script>
