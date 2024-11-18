@extends('layouts.app')
@section('title', 'Create User')
@section('styles')
    <style>
        .company-field {
            display: none;
        }

        .btnCompanyModal {
            float: right;
        }

        .red-text {
            color: red;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Add User</h5>
            <a href="{{ route('user.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('user.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"
                                id="first_name" placeholder="Enter First Name" required />
                            <label for="first_name">First Name</label>
                            @error('first_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}"
                                id="middle_name" placeholder="Enter Middle Name" required />
                            <label for="middle_name">Middle Name</label>
                            @error('middle_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"
                                id="last_name" placeholder="Enter Last Name" required />
                            <label for="last_name">Last Name</label>
                            @error('last_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="password" id="password" class="form-control" name="password"
                                        value="{{ old('password') }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required data-parsley-errors-container="#password_errors" />
                                    <label for="password">Password</label>
                                    @error('password')
                                        <small class="red-text ml-10" role="alert" style="position: absolute;">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                            <div id="password_errors"></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation" value="{{ old('password_confirmation') }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required data-parsley-errors-container="#password_confirmation_errors" />
                                    <label for="password_confirmation">Confirm Password</label>
                                    @error('password_confirmation')
                                        <small class="red-text ml-10" role="alert" style="position: absolute;">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                            <div id="password_confirmation_errors"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}"
                                id="mobile" placeholder="Enter Mobile" required />
                            <label for="mobile">Mobile</label>
                            @error('mobile')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="Enter Email" required />
                            <label for="email">Email</label>
                            @error('email')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="role_id" name="role_id"
                                aria-label="Default select example" data-placeholder="Select Role" data-allow-clear = "true">
                                <option value="" selected>Select Role</option>
                                @foreach ($roles as $role)
                                    <option @if ($role->id == 2) selected @endif value="{{ $role->id }}">
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label for="role_id">Role</label>
                            @error('role_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('form').parsley();
    </script>
@endsection
