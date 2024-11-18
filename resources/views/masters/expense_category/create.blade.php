@extends('layouts.app')
@section('title', 'Create Expense Category')
@section('styles')
    <style>
        .red-text {
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Add Expense Category</h5>
            <a href="{{ route('expense-category.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('expense-category.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Enter Name" required />
                            <label for="name">Name</label>
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
            </div>
        </form>
    </div>
@endsection
@section('scripts')
<script>
    $('form').parsley();
</script>
@endsection
