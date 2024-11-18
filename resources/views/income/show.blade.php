@extends('layouts.app')
@section('title', 'View Income')
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
            <h5 class="card-title m-0 me-2 text-secondary">View Income</h5>
            <a href="{{ route('income.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="card-title text-secondary">Detail</h5>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>House</h6>
                    <label class="mt-2">{{ $income->house->house_no ?? "" }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Member</h6>
                    <label class="mt-2">{{ $income->member->full_name }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Income Category</h6>
                    <label class="mt-2">{{ $income->incomeCatgory->name }}</label>
                </div>
                <hr>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Financial Year</h6>
                    <label class="mt-2">{{ $income->financial_year }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Paid Date</h6>
                    <label class="mt-2">{{ date('d/m/Y', strtotime($income->paid_date)) }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Amount</h6>
                    <label class="mt-2">{{ $income->amount }}</label>
                </div>
                <hr>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Created By</h6>
                    <label class="mt-2">{{ $income->user->full_name }}</label>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
