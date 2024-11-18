@extends('layouts.app')
@section('title', 'Create Petty Cash')
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
            <h5 class="card-title m-0 me-2 text-secondary">Add Petty Cash</h5>
            <a href="{{ route('petty-cash.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('petty-cash.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="date" id="date"
                                value="{{ old('date') }}" placeholder="Enter Date" required />
                            <label for="date">Date</label>
                            @error('date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                id="name" placeholder="Enter Name" required />
                            <label for="name">Name</label>
                            @error('name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" name="opening_balance" value="{{ old('opening_balance') }}"
                                id="opening_balance" placeholder="Enter Opening Balance" required />
                            <label for="opening_balance">Opening Balance</label>
                            @error('opening_balance')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                                placeholder="Enter Description">{{ old('description') }}</textarea>
                            <label for="description">Description</label>
                            @error('description')
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

        $('#date').flatpickr({
            dateFormat: 'd/m/Y'
        });
    </script>
@endsection
