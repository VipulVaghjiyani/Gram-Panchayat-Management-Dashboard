@extends('layouts.app')
@section('title', 'Update User')
@section('styles')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Update User</h5>
            <a href="{{ route('user.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form action="{{ route('user.update', $user->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name) }}"
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
                            <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
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
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                id="last_name" placeholder="Enter Last Name" required />
                            <label for="last_name">Last Name</label>
                            @error('last_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="mobile" value="{{ old('mobile', $user->mobile) }}"
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
                            <input type="text" class="form-control" name="email" value="{{ old('email', $user->email) }}"
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
                                aria-label="Default select example" data-placeholder="Select Role">
                                <option value="" selected>Select Role</option>
                                @foreach ($roles as $role)
                                    <option @if ( $user->role_id  == $role->id) selected @endif value="{{ $role->id }}">
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
