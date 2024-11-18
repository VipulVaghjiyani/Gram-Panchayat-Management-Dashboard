@extends('layouts.app')
@section('title', 'Update Expense')
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
            <h5 class="card-title m-0 me-2 text-secondary">Update Expense</h5>
            <a href="{{ route('expense.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('expense.update', $expense->id) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="member_id" name="member_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Member">
                                <option value="" selected>Select Member</option>
                                @foreach ($members as $member)
                                    <option @if ($expense->member_id == $member->id) selected @endif value="{{ $member->id }}">
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
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="expense_member_id" name="expense_member_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Expense Member" disabled>
                                <option value="" selected>Select Expense Member</option>
                                @foreach ($expense_members as $expense_member)
                                    <option @if ($expense->expense_member_id == $expense_member->id) selected @endif value="{{ $expense_member->id }}">
                                        {{ $expense_member->name }}</option>
                                @endforeach
                            </select>
                            <label for="expense_member_id">Expense Member</label>
                            @error('expense_member_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="account_id" name="account_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Account">
                                <option value="" selected>Select Account</option>
                                @foreach ($accounts as $account)
                                    <option @if ($expense->account_id == $account->id) selected @endif
                                        value="{{ $account->id }}">
                                        {{ $account->name }}</option>
                                @endforeach
                            </select>
                            <label for="account_id">Account</label>
                            @error('account_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="expense_category_id" name="expense_category_id"
                                aria-label="Default select example" data-allow-clear="true"
                                data-placeholder="Select Expense Category" required>
                                <option value="" selected>Select Expense Category</option>
                                @foreach ($expense_categories as $expense_category)
                                    <option @if ($expense->expense_category_id == $expense_category->id) selected @endif
                                        value="{{ $expense_category->id }}">
                                        {{ $expense_category->name }}</option>
                                @endforeach
                            </select>
                            <label for="expense_category_id">Expense Category</label>
                            @error('expense_category_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-merge mb-4">
                            <span class="input-group-text">₹</span>
                            <div class="form-floating form-floating-outline">
                                <input type="number" class="form-control" step="any" min="0" name="amount"
                                    id="amount" value="{{ $expense->amount }}" placeholder="Enter amount" disabled />
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
                                    data-allow-clear="true" data-placeholder="Choose Payment Type" required>
                                    <option value="">Choose Payment Type</option>
                                    <option @if ($expense->payment_type == 'Cash') selected @endif value="Cash">Cash</option>
                                    <option @if ($expense->payment_type == 'Bank') selected @endif value="Bank">Bank Transfer
                                    </option>
                                    <option @if ($expense->payment_type == 'Cheque') selected @endif value="Cheque">Cheque</option>
                                    <option @if ($expense->payment_type == 'Card') selected @endif value="Card">Credit Card
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
                                value="{{ $expense->transaction_number }}" placeholder="Transaction Number" />
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
                                value="{{ date('d/m/Y', strtotime($expense->transaction_date)) }}" placeholder="Transaction Date" />
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
                                value="{{ $expense->bank_name }}" placeholder="Bank Name" />
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
                                value="{{ $expense->cheque_number }}" placeholder="Cheque Number" />
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
                            <input type="text" class="form-control" name="date" value="{{ date('d/m/Y', strtotime($expense->date))  }}"
                                placeholder="DD/MM/YYYY" id="date" required />

                            <label for="date">Date</label>
                            @error('date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control" name="note" id="note" cols="30" rows="10" placeholder="Enter Note">{{ $expense->note }}</textarea>
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
        $(document).ready(function() {
            $('form').parsley();

            $('#date').flatpickr({
                dateFormat: 'd/m/Y',
            });

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
