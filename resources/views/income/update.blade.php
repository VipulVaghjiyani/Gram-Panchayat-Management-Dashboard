@extends('layouts.app')
@section('title', 'Update Income')
@section('styles')
    <style>
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
        .input-group-text{
            background-color: #80808014;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Update Income</h5>
            <a href="{{ route('income.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('income.update', $income->id) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="house_id" name="house_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Member" disabled>
                                <option value="" disabled selected>Choose House</option>
                                <option value="0" selected>All</option>
                                @foreach ($houses as $house)
                                    <option @if ($income->house_id == $house->id) selected @endif value="{{ $house->id }}">
                                        {{ $house->house_no }}</option>
                                @endforeach
                            </select>
                            <label for="house_id">House</label>
                            @error('house_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="member_id" name="member_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Member" disabled>
                                <option value="" selected>Select Member</option>
                                @foreach ($members as $member)
                                    <option @if ($income->member_id == $member->id) selected @endif value="{{ $member->id }}">
                                        {{ $member->full_name }}</option>
                                @endforeach
                            </select>
                            <label for="member_id">Member</label>
                            @error('member_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="income_category_id" name="income_category_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Income Category" disabled>
                                <option value="" selected>Select Income Category</option>
                                @foreach ($income_categories as $income_category)
                                    <option @if ($income->income_category_id == $income_category->id) selected @endif
                                        value="{{ $income_category->id }}">
                                        {{ $income_category->name }}</option>
                                @endforeach
                            </select>
                            <label for="income_category_id">Income Category</label>
                            @error('income_category_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 pani_vero">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" step="any" min="0" name="total_member"
                                    id="total_member" value="{{ $income->total_member }}" placeholder="Enter No. Of Year" value="1"  />
                                <label for="total_member">Total Member</label>
                            @error('total_member')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                     <div class="col-md-4 pani_vero">
                        <div class="form-floating form-floating-outline mb-4">
                            <select name="no_of_year" id="no_of_year" class="select2 form-select"
                                data-allow-clear="true" data-placeholder="Choose Year">
                                <option value="">Choose Year</option>
                                <option @if ($income->no_of_year == '1') selected @endif value="1">1</option>
                                <option @if ($income->no_of_year == '2') selected @endif value="2">2</option>
                                <option @if ($income->no_of_year == '3') selected @endif value="3">3</option>
                                <option @if ($income->no_of_year == '4') selected @endif value="4">4</option>
                                <option @if ($income->no_of_year == '5') selected @endif value="5">5</option>
                                <option @if ($income->no_of_year == '6') selected @endif value="6">6</option>
                                <option @if ($income->no_of_year == '7') selected @endif value="7">7</option>
                                <option @if ($income->no_of_year == '8') selected @endif value="8">8</option>
                                <option @if ($income->no_of_year == '9') selected @endif value="9">9</option>
                                <option @if ($income->no_of_year == '10') selected @endif value="10">10</option>
                            </select>
                            {{-- <input type="number" class="form-control" step="any" min="0" name="no_of_year"
                                    id="no_of_year" value="{{ $income->no_of_year }}" placeholder="Enter No. Of Year" value="1" /> --}}
                                <label for="no_of_year">No. Of Year</label>
                            @error('no_of_year')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="from_date" value="{{ date('d/m/Y', strtotime($income->from_date)) }}"
                                placeholder="DD/MM/YYYY" id="from_date" />
                            <label for="from_date">From Date</label>
                            @error('from_date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="to_date" value="{{ date('d/m/Y', strtotime($income->to_date)) }}"
                                placeholder="DD/MM/YYYY" id="to_date" />
                            <label for="to_date">To Date</label>
                            @error('to_date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-merge mb-4">
                            <span class="input-group-text" >₹</span>
                            <div class="form-floating form-floating-outline">
                                <input type="number" class="form-control" step="any" min="0" name="amount"
                                    id="amount" value="{{ $income->amount }}" placeholder="Enter amount" disabled/>
                                <label for="amount">Amount</label>
                                @error('amount')
                                    <small class="red-text ml-10" role="alert"
                                        style="position: absolute; margin-left: -25px;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="payment_type" id="payment_type" class="select2 form-select"
                                    data-allow-clear="true" data-placeholder="Choose Payment Type">
                                    <option value="">Choose Payment Type</option>
                                    <option @if ($income->payment_type == 'Cash') selected @endif value="Cash">Cash</option>
                                    <option @if ($income->payment_type == 'Bank') selected @endif value="Bank">Bank Transfer
                                    </option>
                                    <option @if ($income->payment_type == 'Cheque') selected @endif value="Cheque">Cheque</option>
                                    <option @if ($income->payment_type == 'Card') selected @endif value="Card">Credit Card
                                    </option>
                                </select>
                                <label for="payment_type">Payment Type</label>
                                @error('payment_type')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 transaction_number_div d-none">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="transaction_number" id="transaction_number"
                                value="{{ $income->transaction_number }}" placeholder="Transaction Number" />
                            <label for="transaction_number">Transaction Number</label>
                            @error('transaction_number')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 transaction_date_div d-none">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="transaction_date" id="transaction_date"
                                value="{{ date('d/m/Y', strtotime($income->transaction_date)) }}" placeholder="Transaction Date" />
                            <label for="transaction_date">Transaction Date</label>
                            @error('transaction_date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 bank_name_div d-none">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="bank_name" id="bank_name"
                                value="{{ $income->bank_name }}" placeholder="Bank Name" />
                            <label for="bank_name">Bank Name</label>
                            @error('bank_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 cheque_number_div d-none">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="cheque_number" id="cheque_number"
                                value="{{ $income->cheque_number }}" placeholder="Cheque Number"/>
                            <label for="cheque_number">Cheque Number</label>
                            @error('cheque_number')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="paid_date" value="{{ date('d/m/Y', strtotime($income->paid_date)) }}"
                                placeholder="DD/MM/YYYY" id="paid_date" />
                            <label for="paid_date">Paid Date</label>
                            @error('paid_date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 pani_vero">
                        <div class="mt-2">
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_taxable" @if ($income->is_taxable == 1) checked @endif />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Taxable</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 pani_vero">
                        <div class="mt-2">
                            <label class="switch switch-primary">
                                <input type="checkbox" class="switch-input" name="is_late_paid" @if ($income->is_late_paid == 1) checked @endif />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Late Paid</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control" name="note" id="note" cols="30" rows="10" placeholder="Enter Note">{{ $income->note }}</textarea>
                            <label for="note">Note</label>
                            @error('note')
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
        $(document).ready(function() {
            $('#paid_date, #from_date, #to_date').flatpickr({
                dateFormat: 'd/m/Y',
            });

            // $('#house_id').on('change', function () {
               var houseId = $('#house_id').val();
                $.ajax({
                    type: "post",
                    url: "/baladia/public/income/house-member",
                    data: {
                        house_id: houseId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: "json",
                    success: function (response) {
                        $('#member_id').html('<option value="" selected>Select Member</option>');
                        $.each(response, function (key, value) {
                            var option = '<option value="' + value.id + '">' + value.first_name + ' ' + value.middle_name + ' ' + value.last_name + '</option>';
                            if (value.id == {{$income->member_id}}) {
                                option = $(option).prop('selected', true);
                            }
                            $('#member_id').append(option);
                        });
                    }
                });
            // });

           var houseId1 = $('#house_id').val();
            $.ajax({
                type: "get",
                url: "/baladia/public/member/fetch-house-address/" + houseId1,
                success: function(response) {
                    $('#total_member').val(response.total_members);
                }
            });
                // $('#amount').on('click', function () {
                // var total_member = $('#total_member').val();
                // });

            $('#income_category_id').on('change', function () {
                var value = $(this).val();

                if (value == 1) {
                    $('.pani_vero').removeClass("d-none");
                    $('#total_member').attr("required", true);
                    $('#no_of_year').attr("required", true);

                } else {
                    $('.pani_vero').addClass("d-none");
                    $('#total_member').attr("required", false);
                    $('#no_of_year').attr("required", false);
                }
            });

            if ($('#income_category_id').val() == 1) {
                $('.pani_vero').removeClass("d-none");
            } else {
                $('.pani_vero').addClass("d-none");
            }

            if (($('#payment_type').val() == 'Bank') || ($('#payment_type').val() == 'Card')) {
                $('.transaction_number_div').removeClass("d-none");
                $('.transaction_date_div').removeClass("d-none");
                $('#transaction_number').attr("required", true);
                $('#transaction_date').attr("required", true);
                $('.bank_name_div').addClass("d-none");
                $('.cheque_number_div').addClass("d-none");
                $('#bank_name').attr("required", false);
                $('#cheque_number').attr("required", false);
                $('#transaction_date').flatpickr({
                    dateFormat: 'd/m/Y'
                });
            } else if ($('#payment_type').val() == 'Cheque') {
                $('.transaction_number_div').addClass("d-none");
                $('.transaction_date_div').addClass("d-none");
                $('#transaction_number').attr("required", false);
                $('#transaction_date').attr("required", false);
                $('.bank_name_div').removeClass("d-none");
                $('.cheque_number_div').removeClass("d-none");
                $('#bank_name').attr("required", true);
                $('#cheque_number').attr("required", true);
            } else {
                $('.transaction_number_div').addClass("d-none");
                $('.transaction_date_div').addClass("d-none");
                $('.bank_name_div').addClass("d-none");
                $('.cheque_number_div').addClass("d-none");
                $('#transaction_number').attr("required", false);
                $('#transaction_date').attr("required", false);
                $('#bank_name').attr("required", false);
                $('#cheque_number').attr("required", false);
            }

            $('#payment_type').on('change', function() {
                if (($(this).val() == 'Bank') || ($(this).val() == 'Card')) {
                    $('.transaction_number_div').removeClass("d-none");
                    $('.transaction_date_div').removeClass("d-none");
                    $('#transaction_number').attr("required", true);
                    $('#transaction_date').attr("required", true);
                    $('.bank_name_div').addClass("d-none");
                    $('.cheque_number_div').addClass("d-none");
                    $('#bank_name').attr("required", false);
                    $('#cheque_number').attr("required", false);
                    $('#submit').on('click', function() {
                        var transaction_number = $('#transaction_number').val();
                        var transaction_date = $('#transaction_date').val();
                        $(".error").remove();
                        if (transaction_number.length < 1) {
                            $('#transaction_number').after(
                                '<span class="error">This field is required</span>');
                        }
                        if (transaction_date.length < 1) {
                            $('#transaction_date').after(
                                '<span class="error">This field is required</span>');
                        }
                    });
                    $('#transaction_date').flatpickr({
                        dateFormat: 'd/m/Y'
                    });
                } else if ($(this).val() == 'Cheque') {
                    $('.transaction_number_div').addClass("d-none");
                    $('.transaction_date_div').addClass("d-none");
                    $('#transaction_number').attr("required", false);
                    $('#transaction_date').attr("required", false);
                    $('.bank_name_div').removeClass("d-none");
                    $('.cheque_number_div').removeClass("d-none");
                    $('#bank_name').attr("required", true);
                    $('#cheque_number').attr("required", true);
                    $('#submit').on('click', function() {
                        var bank_name = $('#bank_name').val();
                        var cheque_number = $('#cheque_number').val();
                        $(".error").remove();
                        if (bank_name.length < 1) {
                            $('#bank_name').after(
                                '<span class="error">This field is required</span>');
                        }
                        if (cheque_number.length < 1) {
                            $('#cheque_number').after(
                                '<span class="error">This field is required</span>');
                        }
                    });
                } else {
                    $('.transaction_number_div').addClass("d-none");
                    $('.transaction_date_div').addClass("d-none");
                    $('.bank_name_div').addClass("d-none");
                    $('.cheque_number_div').addClass("d-none");
                    $('#transaction_number').attr("required", false);
                    $('#transaction_date').attr("required", false);
                    $('#bank_name').attr("required", false);
                    $('#cheque_number').attr("required", false);
                }
            });
        });
    </script>
@endsection
