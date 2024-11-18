@extends('layouts.app')
@section('title', 'View Member')
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

        @media screen and (max-width:767px) {
            .border-right {
                border-right: none;
            }
        }

        @media screen and (max-width: 1440px) {
            .table-responsive {
                overflow: scroll;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">View Member</h5>
            <a href="{{ route('member.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="card-title text-secondary">Detail</h5>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Name</h6>
                    <label class="mt-2">{{ $member->full_name }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Mobile</h6>
                    <label class="mt-2">{{ $member->mobile }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Email</h6>
                    <label class="mt-2">{{ $member->email }}</label>
                </div>
                <hr>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>House No</h6>
                    <label class="mt-2">{{ $member->house->house_no ?? '' }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Created by</h6>
                    <label class="mt-2">{{ $member->user->full_name }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>House Hold Type</h6>
                    <label class="mt-2">{{ $member->house_hold_type }}</label>
                </div>
                <hr>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Is Income Member</h6>
                    <label class="mt-2">{{ $member->is_income_member ? 'Yes' : 'No' }}</label>
                </div>
                <div class="col-md-4 mb-2 border-right mb-md-0">
                    <h6>Is Expense Member</h6>
                    <label class="mt-2">{{ $member->is_expance_member ? 'Yes' : 'No' }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Customer Number</h6>
                    <label class="mt-2">{{ $member->customer_no ?? '-' }}</label>
                </div>
                <hr>

                <div class="col-md-6 border-right mb-2 mb-md-0">
                    <h6>Permanent Address</h6>
                    <label class="mt-2">{{ $memberPermanentAddress->full_permanent_address ?? '' }}</label>
                </div>
                <div class="col-md-6 mb-2 mb-md-0">
                    <h6>Current Address</h6>
                    <label class="mt-2">{{ $memberCurrentAddress->full_current_address ?? '' }}</label>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 mt-4" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-house" aria-controls="navs-pills-top-house"
                                    aria-selected="true">
                                    Houses
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-income" aria-controls="navs-pills-top-income"
                                    aria-selected="true">
                                    Income
                                </button>
                            </li>
                        </ul>
                        <hr>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-top-house" role="tabpanel">
                                <div class="table-responsive text-nowrap">
                                    <table class="table" id="houseTable">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>House No</th>
                                                <th>House Hold Type</th>
                                                <th>Address</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @foreach ($houses as $key => $value)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->house->house_no }}</td>
                                                    <td>{{ $value->house_hold_type }}</td>
                                                    <td>{{ $value->house->full_address }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="navs-pills-top-income" role="tabpanel">
                                <div class="table-responsive text-nowrap">
                                    <table class="table" id="incomeTable">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>Financial Year</th>
                                                <th>Paid Date</th>
                                                <th>Amount</th>
                                                <th>Payment Type</th>
                                                <th>No. Of Year</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @foreach ($incomes as $key => $value)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->financial_year }}</td>
                                                    <td>{{ $value->paid_date ? \DateTime::createFromFormat('Y-m-d', $value->paid_date)->format('d/m/Y') : '' }}</td>
                                                    <td>{{ $value->amount }}</td>
                                                    <td>{{ $value->payment_type }}</td>
                                                    <td>{{ $value->no_of_year }}</td>
                                                    <td>{{ $value->from_date ? \DateTime::createFromFormat('Y-m-d', $value->from_date)->format('d/m/Y') : '' }}</td>
                                                    <td>{{ $value->to_date ? \DateTime::createFromFormat('Y-m-d', $value->to_date)->format('d/m/Y') : '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#houseTable').DataTable();
            $('#incomeTable').DataTable();
        });
    </script>
@endsection
