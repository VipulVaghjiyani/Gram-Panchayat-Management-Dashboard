@extends('layouts.app')
@section('title', 'View User')
@section('styles')
    <style>
        h6 {
            margin: 10px;
        }

        label {
            margin-left: 8px;
        }

        .card-body hr {
            margin-top: 15px;
            border: 1px solid #D8D8DD
        }

        .border-right {
            border-right: 1px solid #D8D8DD;
        }
    </style>
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">View User</h5>
            <a href="{{ route('user.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="card-title text-secondary">Detail</h5>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Name</h6>
                    <label class="mt-2">{{ $user->full_name }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Mobile</h6>
                    <label class="mt-2">{{ $user->mobile }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Email</h6>
                    <label class="mt-2">{{ $user->email }}</label>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
