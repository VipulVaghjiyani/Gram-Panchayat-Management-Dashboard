@extends('layouts.app')
@section('title', 'View Petty Cash')
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

        @media screen and (max-width: 1440px){
            .table-responsive {
                overflow: scroll;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header">
            <div class="row mb-4">
                <h5 class="card-title m-0 me-2 text-secondary">View Petty Cash</h5>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('petty-cash.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
                </div>
                {{-- <div class="col-md-6 text-end">
                    <a href="{{ route('petty-cash.edit', $data->id) }}" class="btn btn-primary waves-effect waves-light">Update</a>
                </div> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="card-title text-secondary">Detail</h5>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Name</h6>
                    <label class="mt-2">{{ $data->name }}</label>
                </div>
                <div class="col-md-4 mb-2 border-right mb-md-0">
                    <h6>Date</h6>
                    <label class="mt-2">{{ $data->date }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Description</h6>
                    <label class="mt-2">{{ $data->description }}</label>
                </div>
                <hr>
                <div class="col-md-4 mb-2 border-right mb-md-0">
                    <h6>Amount</h6>
                    <label class="mt-2">{{ $data->amount }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Cash In Hand</h6>
                    <label class="mt-2">{{ $data->cash_in_hand }}</label>
                </div>
                <hr>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mt-4">
                    <div class="card">
                        <h5 class="card-header">Item Details</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    @php
                                    $pettyCashData = $data->pettyCashLog;
                                    @endphp

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($pettyCashData->isNotEmpty())
                                            @foreach($pettyCashData as $key => $items)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $items->name }}</td>
                                                <td>{{ $items->description }}</td>
                                                <td>{{ $items->amount }}</td>
                                                <td>{{ $items->type }}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan=7>No records found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                          <tr>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <th>
                                                <div class="m4">
                                                  Cash In Hand : {{ $data->cash_in_hand }}
                                                </div>
                                              </th>
                                              <td></td>
                                          </tr>
                                      </tfoot>
                                    </table>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
