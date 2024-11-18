@extends('layouts.app')
@section('title', 'View House')
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

            .house_selection_input {
                width: auto;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">View House</h5>
            <a href="{{ route('house.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="card-title text-secondary">Detail</h5>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>House No</h6>
                    <label class="mt-2">{{ $house->house_no }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>
                        Address
                    </h6>
                    <label class="mt-2">{{ $house->address }}</label>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <h6>Total Members</h6>
                    <label class="mt-2">{{ $house->total_members }}</label>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 mt-4" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-donation" aria-controls="navs-pills-top-donation"
                                    aria-selected="true">
                                    Members
                                </button>
                            </li>
                        </ul>
                        <hr>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-top-donation" role="tabpanel">
                                <div class="table-responsive text-nowrap">
                                    <table class="table" id="memberTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>House Hold Type</th>
                                                <th>Currently living</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @foreach ($houseMember as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->member->full_name ?? "" }}</td>
                                                    <td>{{ $item->member->mobile ?? "" }}</td>
                                                    <td>{{ $item->house_hold_type }}</td>
                                                    <td>{{ $item->is_currently_living == true ? 'Yes' : 'No' }}</td>
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
            $('#memberTable').DataTable();
        });
    </script>
@endsection
