@extends('layouts.app')
@section('title', 'View Bank')
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

        @media screen and (max-width:767px){
            .border-right {
                border-right: none;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">View Bank</h5>
            <a href="{{ route('bank.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="card-title text-secondary">Detail</h5>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Bank Name</h6>
                    <label class="mt-2">{{ $bank->name }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Account Name</h6>
                    <label class="mt-2">{{ $bank->account_name }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Account Number</h6>
                    <label class="mt-2">{{ $bank->account_number }}</label>
                </div>
                <hr>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>IFCS Code</h6>
                    <label class="mt-2">{{ $bank->ifcs_code }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Branch</h6>
                    <label class="mt-2">{{ $bank->branch }}</label>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
