@extends('layouts.app')
@section('title', 'Create Member')
@section('styles')
    <style>
        .parsley-required,
        .parsley-errors-list li,
        .red-text {
            color: red;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .house_selection {
            display: none;
        }

        #memberHouseTable tr td:first-child {
            padding-left: 0px;
        }

        .form-floating-outline :not(select):focus+span {
            color: red !important;
        }

        @media screen and (max-width: 1440px) {
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
    {{-- <div class="card"> --}}
    <div class="card-header d-flex align-items-center justify-content-between mb-2">
        <h5 class="card-title m-0 me-2 text-secondary">Add Member</h5>
        <a href="{{ route('member.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
    </div>
    <form method="POST" enctype="multipart/form-data" action="{{ route('member.store') }}" id="memberForm">
        @csrf
        <div class="card">
            <h5 class="card-header">Personal Details</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="mt-2">
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_income_member" id="is_income_member"
                                    checked />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Income Member</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="mt-2">
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_expance_member"
                                    id="is_expance_member" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Expense Member</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"
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
                            <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}"
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
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"
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
                            <input type="text" class="form-control" name="dob" value="{{ old('dob') }}"
                                placeholder="DD/MM/YYYY" id="dob" />
                            <label for="dob">DOB</label>
                            @error('dob')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" name="mobile" value="{{ old('mobile') }}"
                                id="mobile" placeholder="Enter Mobile" required minlength="7" />
                            <label for="mobile">Mobile</label>
                            @error('mobile')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" name="alternate_number"
                                value="{{ old('alternate_number') }}" id="alternate_number"
                                placeholder="Enter Alternate Number" minlength="7" />
                            <label for="alternate_number">Alternate Number</label>
                            @error('alternate_number')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="Enter Email" />
                            <label for="email">Email</label>
                            @error('email')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="customer_no"
                                value="{{ old('customer_no') }}" placeholder="Enter Customer Number" id="customer_no" />
                            <label for="customer_no">Customer Number</label>
                            @error('customer_no')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-4">House Details</h5>
                <div class="row add_house_details">
                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table" id="memberHouseTable">
                                <tbody>
                                    @if(old('memberHouse'))
                                        @foreach(old('memberHouse') as $key => $memberHouse)
                                            <tr class="row" style="margin-left: 0;">
                                                <td class="col-md-3">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <select class="form-select select2 house_id"
                                                                name="memberHouse[{{ $key }}][house_id]"
                                                                id="house_id_{{ $key }}"
                                                                aria-label="Default select example"
                                                                data-placeholder="Select House"
                                                                data-parsley-errors-container="#house_id_{{ $key }}_errors" required>
                                                            <option value="" selected>Select House</option>
                                                            @foreach ($houses as $house)
                                                                <option @if (old("memberHouse.$key.house_id") == $house->id) selected @endif
                                                                    value="{{ $house->id }}">
                                                                    {{ $house->house_no }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="house_id_{{ $key }}_errors"></div>
                                                        <label for="house_id_{{ $key }}">House</label>
                                                        @error("memberHouse.$key.house_id")
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </td>

                                                <td class="col-md-3">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <select name="memberHouse[{{ $key }}][house_hold_type]"
                                                                id="house_hold_type_{{ $key }}"
                                                                class="select2 form-select house_hold_type"
                                                                data-placeholder="Select House Hold Type"
                                                                data-parsley-errors-container="#house_hold_type_{{ $key }}_errors" required>
                                                            <option value="">Select House Hold Type</option>
                                                            <option @if (old("memberHouse.$key.house_hold_type") == 'Owner') selected @endif value="Owner">
                                                                Owner
                                                            </option>
                                                            <option @if (old("memberHouse.$key.house_hold_type") == 'Owner Member') selected @endif value="Owner Member">
                                                                Owner Member
                                                            </option>
                                                            <option @if (old("memberHouse.$key.house_hold_type") == 'Rental') selected @endif value="Rental">
                                                                Rental
                                                            </option>
                                                        </select>
                                                        <div id="house_hold_type_{{ $key }}_errors"></div>
                                                        <label for="house_hold_type_{{ $key }}">House Hold Type</label>
                                                        @error("memberHouse.$key.house_hold_type")
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </td>
                                                <td class="col-md-3">
                                                    <div class="mt-2">
                                                        <label class="switch switch-primary">
                                                            <input type="checkbox" class="switch-input is_currently_living"
                                                                name="memberHouse[{{ $key }}][is_currently_living]"
                                                                @if (old("memberHouse.$key.is_currently_living") == 'on') checked @endif />
                                                            <span class="switch-toggle-slider">
                                                                <span class="switch-on"></span>
                                                                <span class="switch-off"></span>
                                                            </span>
                                                            <span class="switch-label">Currently Living</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="col-md-3">
                                                    <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                                                        <a class="btn btn-secondary btn-remove-house text-white" data-id="{{ $key }}">Remove</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                        <tr class="row" style="margin-left: 0;">
                                            <td class="col-md-3">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <select class="form-select select2 house_id"
                                                            name="memberHouse[0][house_id]"
                                                            id="house_id_0"
                                                            aria-label="Default select example"
                                                            data-placeholder="Select House"
                                                            data-parsley-errors-container="#house_id_0_errors" required>
                                                        <option value="" selected>Select House</option>
                                                        @foreach ($houses as $house)
                                                            <option value="{{ $house->id }}">
                                                                {{ $house->house_no }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="house_id_0_errors"></div>
                                                    <label for="house_id_0">House</label>
                                                    @error("memberHouse.0.house_id")
                                                    <small class="red-text ml-10" role="alert">
                                                        {{ $message }}
                                                    </small>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <select name="memberHouse[0][house_hold_type]"
                                                            id="house_hold_type_0"
                                                            class="select2 form-select house_hold_type"
                                                            data-placeholder="Select House Hold Type"
                                                            data-parsley-errors-container="#house_hold_type_0_errors" required>
                                                        <option value="">Select House Hold Type</option>
                                                        <option value="Owner">Owner</option>
                                                        <option value="Owner Member">Owner Member</option>
                                                        <option value="Rental">Rental</option>
                                                    </select>
                                                    <div id="house_hold_type_0_errors"></div>
                                                    <label for="house_hold_type_0">House Hold Type</label>
                                                    @error("memberHouse.0.house_hold_type")
                                                    <small class="red-text ml-10" role="alert">
                                                        {{ $message }}
                                                    </small>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div class="mt-2">
                                                    <label class="switch switch-primary">
                                                        <input type="checkbox" class="switch-input is_currently_living"
                                                               name="memberHouse[0][is_currently_living]" />
                                                        <span class="switch-toggle-slider">
                                                            <span class="switch-on"></span>
                                                            <span class="switch-off"></span>
                                                        </span>
                                                        <span class="switch-label">Currently Living</span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                                                    <a class="btn btn-secondary btn-remove-house text-white" data-id="0">Remove</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <a class="btn btn-secondary text-white mt-2 mb-2" id="btn-add-house">Add House </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <h5 class="card-header">Permanent Address Details</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_address"
                                value="{{ old('permanent_address') }}" id="permanent_address"
                                placeholder="Enter Permanent Address" required />
                            <label for="permanent_address">Permanent Address</label>
                            @error('permanent_address')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_gaam"
                                value="{{ old('permanent_gaam', 'Baladiya') }}" id="permanent_gaam"
                                placeholder="Enter Gaam" />
                            <label for="permanent_gaam">Gaam</label>
                            @error('permanent_gaam')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_taluka"
                                value="{{ old('permanent_taluka', 'Bhuj') }}" id="permanent_taluka"
                                placeholder="Enter Taluka" />
                            <label for="permanent_taluka">City</label>
                            @error('permanent_taluka')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_district"
                                value="{{ old('permanent_district', 'Kachchh') }}" id="permanent_district"
                                placeholder="Enter District" />
                            <label for="permanent_district">District</label>
                            @error('permanent_district')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_state"
                                value="{{ old('permanent_state', 'Gujarat') }}" id="permanent_state"
                                placeholder="Enter State" />
                            <label for="permanent_state">State</label>
                            @error('permanent_state')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_country"
                                value="{{ old('permanent_country', 'India') }}" id="permanent_country"
                                placeholder="Enter Country" />
                            <label for="permanent_country">Country</label>
                            @error('permanent_country')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="permanent_post_code"
                                value="{{ old('permanent_post_code', '370427') }}" id="permanent_post_code"
                                placeholder="Enter Post Code" />
                            <label for="permanent_post_code">Post Code</label>
                            @error('permanent_post_code')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <h5 class="card-header">Current Address Details</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="mt-2">
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_same_as_permanent_address"
                                    id="is_same_as_permanent_address" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Same As Permanent Address</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_address"
                                value="{{ old('current_address') }}" id="current_address"
                                placeholder="Enter Current Address" required />
                            <label for="current_address">Current Address</label>
                            @error('current_address')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_gaam"
                                value="{{ old('current_gaam') }}" id="current_gaam" placeholder="Enter Gaam" />
                            <label for="current_gaam">Gaam</label>
                            @error('current_gaam')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_taluka"
                                value="{{ old('current_taluka') }}" id="current_taluka" placeholder="Enter Taluka" />
                            <label for="current_taluka">City</label>
                            @error('current_taluka')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_district"
                                value="{{ old('current_district') }}" id="current_district"
                                placeholder="Enter District" />
                            <label for="current_district">District</label>
                            @error('current_district')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_state"
                                value="{{ old('current_state') }}" id="current_state" placeholder="Enter State" />
                            <label for="current_state">State</label>
                            @error('current_state')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_country"
                                value="{{ old('current_country') }}" id="current_country" placeholder="Enter Country" />
                            <label for="current_country">Country</label>
                            @error('current_country')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="current_post_code"
                                value="{{ old('current_post_code') }}" id="current_post_code"
                                placeholder="Enter Post Code" />
                            <label for="current_post_code">Post Code</label>
                            @error('current_post_code')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end pt-0 mt-2">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    {{-- </div> --}}
@endsection
@section('scripts')
    <script>
        $('form').parsley();
        $(document).ready(function() {
            $('.select2').select2();

            $('#dob').flatpickr({
                dateFormat: 'd/m/Y',
                maxDate: 'today'
            });

            var counter = $('#memberHouseTable tbody tr').length;

            $('#btn-add-house').on('click', function() {
                ++counter;
                var memberHouseHtml = '';
                memberHouseHtml = '<tr class="row" style="margin-left: 0;">' +
                    '<td class="col-md-3">' +
                    '<div class="form-floating form-floating-outline mb-4">' +
                    '<select class="form-select select2 house_id" id="house_id_' + counter +
                    '" name="memberHouse[' + (counter) +
                    '][house_id]"' +
                    'aria-label="Default select example" data-placeholder="Select House"' +
                    'data-parsley-errors-container="#house_id_' + counter + '_errors" required>' +
                    '<option value="" selected>Select House</option>' +
                    '@foreach ($houses as $house)' +
                    '<option @if (old("memberHouse.' + counter + '.house_id") == $house->id) selected @endif' +
                    'value="{{ $house->id }}">' +
                    '{{ $house->house_no }}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '<div id="house_id_' + counter + '_errors"></div>' +
                    '<label for="house_id">House</label>' +
                    '@error("house_id")' +
                    '<small class="red-text ml-10" role="alert">' +
                    '{{ $message }}' +
                    '</small>' +
                    '@enderror' +
                    '</div>' +
                    '</td>' +
                    '<td class="col-md-3">' +
                    '<div class="form-floating form-floating-outline mb-4">' +
                    '<select name="memberHouse[' + (counter) +
                    '][house_hold_type]" id="house_hold_type_' + counter + '"' +
                    'class="select2 form-select house_hold_type" data-placeholder="Select House Hold Type"' +
                    'data-parsley-errors-container="#house_hold_type_' + counter + '_errors" required>' +
                    '<option value="">Select House Hold Type</option>' +
                    '<option @if (old("memberHouse.' + counter + '.house_hold_type") == "Owner") selected @endif' +
                    'value="Owner">' +
                    'Owner</option>' +
                    '<option @if (old("memberHouse.' + counter + '.house_hold_type") == "Owner Member") selected @endif' +
                    'value="Owner Member">' +
                    'Owner Member</option>' +
                    '<option @if (old("memberHouse.' + counter + '.house_hold_type") == "Rental") selected @endif' +
                    'value="Rental">Rental' +
                    '</option>' +
                    '</select>' +
                    '<div id="house_hold_type_' + counter + '_errors"></div>' +
                    '<label for="house_hold_type">House Hold Type</label>' +
                    '@error("house_hold_type_' + counter + '")' +
                    '<small class="red-text ml-10" role="alert">' +
                    '{{ $message }}' +
                    '</small>' +
                    '@enderror' +
                    '</div>' +
                    '</td>' +
                    '<td class="col-md-3">' +
                    '<div class="mt-2">' +
                    '<label class="switch switch-primary">' +
                    '<input type="checkbox" class="switch-input is_currently_living"' +
                    'name="memberHouse[' + (counter) +
                    '][is_currently_living]"' +
                    '@if (old("memberHouse.' + counter + '.is_currently_living") == "on") checked @endif />' +
                    '<span class="switch-toggle-slider">' +
                    '<span class="switch-on"></span>' +
                    '<span class="switch-off"></span>' +
                    '</span>' +
                    '<span class="switch-label">Currently Living</span>' +
                    '</label>' +
                    '</div>' +
                    '</td>' +
                    '<td class="col-md-3">' +
                    '<div class="d-flex align-items-center mb-4" style="gap: 10px;">' +
                    '<a class="btn btn-secondary btn-remove-house text-white"' +
                    'data-id="'+counter+'">Remove</a>' +
                    '</div>' +
                    '</td>' +
                    '</tr>';

                $('#memberHouseTable').append(memberHouseHtml);
                $('#house_id_' + counter).on('change', checkForDuplicateHouses);

                $('.select2').select2();
            });

            $(document).on('change', '.house_id', checkForDuplicateHouses);

            function checkForDuplicateHouses() {
                var selectedHouses = [];
                var duplicateFound = false;

                $('.house_id').each(function() {
                    var selectedValue = $(this).val();
                    var errorContainerId = $(this).attr('id').replace('house_id_', 'house_id_')+ '_errors';
                    console.log("errorContainerId",errorContainerId);

                    if (selectedValue) {
                        if (selectedHouses.includes(selectedValue)) {
                            duplicateFound = true;
                            $('#' + errorContainerId).html(
                                '<small class="red-text ml-10" role="alert">This house is already selected.</small>'
                                );
                        } else {
                            selectedHouses.push(selectedValue);
                            $('#' + errorContainerId).html('');
                        }
                    }
                });
                return duplicateFound;
            }
            $('form').on('submit', function(e) {
            if (checkForDuplicateHouses()) {
                e.preventDefault();
            }
            });

            $(document).on('change', '.is_currently_living', function() {
                if (!$(this).prop('checked')) {
                    $(this).prop('checked', true);
                }
                var row = $(this).parents('tr').find('select.house_id').val();
                $.ajax({
                    type: "get",
                    url: "{{ route('house.fetch-all') }}",
                    success: function (response) {
                        // $('change').on('.house_id', function () {
                            $.each(response, function (indexInArray, valueOfElement) {
                                if (valueOfElement.id == row) {
                                    $('#permanent_address').val(valueOfElement.address);
                                    $('#permanent_gaam').val(valueOfElement.gaam);
                                    $('#permanent_taluka').val(valueOfElement.taluka);
                                    $('#permanent_district').val(valueOfElement.district);
                                    $('#permanent_state').val(valueOfElement.state);
                                    $('#permanent_country').val(valueOfElement.country);
                                    $('#permanent_post_code').val(valueOfElement.post_code);
                                }
                            });
                        // });
                    }
                });
                $('.is_currently_living').not(this).prop('checked', false);
            });

            $(document).on('click', '.btn-remove-house', function() {
                var row = $(this).closest('tr');
                var house_id = row.find('select.house_id').val();
                var house_hold_type = row.find('select.house_hold_type').val();
                var id = $(this).data('id');
                if (id && house_id && house_hold_type) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to remove.",
                        icon: 'warning',
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'No, Please!',
                        customClass: {
                            confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                            cancelButton: 'btn btn-outline-secondary waves-effect'
                        },
                        buttonsStyling: false
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/baladia/public/house/remove-house-member/' + id,
                                type: "get",
                                data: {
                                    id: id
                                }
                            }).done(function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Removed!',
                                    text: 'Your file has been removed.',
                                    customClass: {
                                        confirmButton: 'btn btn-success waves-effect'
                                    }
                                });
                                row.remove();
                            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cancelled!',
                                    text: 'Something wrong.',
                                    customClass: {
                                        confirmButton: 'btn btn-success waves-effect'
                                    }
                                });
                            })
                        } else {
                            Swal.fire({
                                title: 'Cancelled!',
                                text: 'Record is safe',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        }
                    });
                } else {
                    row.remove();
                }
            });

            /* $('.house_id').on('change', function() {
                var id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "/baladia/public/member/fetch-house-address/" + id,
                    success: function(response) {
                        $('#permanent_address').val(response.address);
                        $('#permanent_gaam').val(response.gaam);
                        $('#permanent_taluka').val(response.taluka);
                        $('#permanent_district').val(response.district);
                        $('#permanent_state').val(response.state);
                        $('#permanent_country').val(response.country);
                        $('#permanent_post_code').val(response.post_code);
                    }
                });
            }); */

            $('#is_same_as_permanent_address').on('click', function() {
                if ($(this).prop('checked') == true) {
                    $('#current_address').val($('#permanent_address').val());
                    $('#current_gaam').val($('#permanent_gaam').val());
                    $('#current_taluka').val($('#permanent_taluka').val());
                    $('#current_district').val($('#permanent_district').val());
                    $('#current_state').val($('#permanent_state').val());
                    $('#current_country').val($('#permanent_country').val());
                    $('#current_post_code').val($('#permanent_post_code').val());
                } else {
                    $('#current_address').val("");
                    $('#current_gaam').val("");
                    $('#current_taluka').val("");
                    $('#current_district').val("");
                    $('#current_state').val("");
                    $('#current_country').val("");
                    $('#current_post_code').val("");
                }

            });
        });
    </script>
@endsection
