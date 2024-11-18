@extends('layouts.app')
@section('title', 'Create Role')
@section('styles')
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Add Role</h5>
            <a href="{{ route('roles.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Enter Role" required />
                            <label for="name">Role</label>
                            @error('name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 text-md-center">
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('form').parsley();
    </script>
@endsection
