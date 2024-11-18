@extends('layouts.app')
@section('title', 'Create Bank')
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
            <h5 class="card-title m-0 me-2 text-secondary">Add Bank</h5>
            <a href="{{ route('bank.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('bank-transaction.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select select2" id="bank_id" name="bank_id"
                                aria-label="Default select example" data-allow-clear="true" data-placeholder="Select Bank"
                                data-parsley-errors-container="#bank_id_errors" required>
                                <option value="" selected>Select Bank</option>
                                @foreach ($banks as $bank)
                                    <option @if (old('bank_id') == $bank->name) selected @endif value="{{ $bank->id }}">
                                        {{ $bank->name . ' ' . $bank->account_number }}</option>
                                @endforeach
                            </select>
                            <div id="bank_id_errors"></div>
                            <label for="bank_id">Bank Name</label>
                            @error('bank_id')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{-- <label class="d-block form-label">Credit/Debit</label> --}}
                        <div class="form-check form-check-inline mt-2">
                            <input type="radio" id="credit" name="type" value="credit"
                                class="form-check-input credit_debit" required="" checked="">
                            <label class="form-check-label" for="basic-default-radio-male">Credit</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="debit" name="type" value="debit"
                                class="form-check-input credit_debit" required="">
                            <label class="form-check-label" for="basic-default-radio-female">Debit</label>
                        </div>
                    </div>
                    <div class="col-md-4 credit_field">
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" name="amt_deposite"
                                value="{{ old('amt_deposite') }}" id="amt_deposite"
                                placeholder="Enter Credited Balance" required />
                            <label for="amt_deposite">Credited Balance</label>
                            @error('amt_deposite')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 debit_field d-none">
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" name="amt_withdrawn" value="{{ old('amt_withdrawn') }}"
                                id="amt_withdrawn" placeholder="Enter Debited Balance" />
                            <label for="amt_withdrawn">Debited Balance</label>
                            @error('amt_withdrawn')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="payment_type" id="payment_type" class="select2 form-select"
                                    data-allow-clear="true" data-placeholder="Choose Payment Type"
                                    data-parsley-errors-container="#payment_type_errors" required>
                                    <option value="">Choose Payment Type</option>
                                    <option @if (old('payment_type') == 'Cash') selected @endif value="Cash">Cash</option>
                                    <option @if (old('payment_type') == 'Bank') selected @endif value="Bank">Bank Transfer
                                    </option>
                                    <option @if (old('payment_type') == 'Cheque') selected @endif value="Cheque">Cheque</option>
                                    <option @if (old('payment_type') == 'Card') selected @endif value="Card">Credit Card
                                    </option>
                                </select>
                                <div id="payment_type_errors"></div>
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
                                value="{{ old('transaction_number') }}" placeholder="Transaction Number" />
                            <label for="transaction_number">Transaction Number</label>
                            @error('transaction_number')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 cheque_number_div d-none">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="cheque_number" id="cheque_number"
                                value="{{ old('cheque_number') }}" placeholder="Cheque Number" />
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
                            <input type="text" class="form-control" name="transaction_date" id="transaction_date"
                                value="{{ old('transaction_date') }}" placeholder="Transaction Date" />
                            <label for="transaction_date">Transaction Date</label>
                            @error('transaction_date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control" name="note" id="note" cols="30" rows="10"
                                placeholder="Enter Note">{{ old('note') }}</textarea>
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

        $('#transaction_date').flatpickr({
            dateFormat: 'd/m/Y'
        });

        $(document).ready(function() {
            $('.credit_debit').on('change', function() {
                console.log($(this).val());
                var value = $(this).val();

                if (value == "credit") {
                    $('.credit_field').removeClass('d-none');
                    $('#amt_deposite').attr('required', true);
                    $('.debit_field').addClass('d-none');
                    $('#amt_withdrawn').attr('required', false);
                } else {
                    $('.credit_field').addClass('d-none');
                    $('#amt_deposite').attr('required', false);
                    $('.debit_field').removeClass('d-none');
                    $('#amt_withdrawn').attr('required', true);
                }
            });

            if (("{{ old('payment_type') }}" == 'Bank') || ("{{ old('payment_type') }}" == 'Card')) {
                $('.transaction_number_div').removeClass("d-none");
                $('#transaction_number').attr("required", true);
                $('.cheque_number_div').addClass("d-none");
                $('#cheque_number').attr("required", false);
            } else if ("{{ old('payment_type') }}" == 'Cheque') {
                $('.transaction_number_div').addClass("d-none");
                $('#transaction_number').attr("required", false);
                $('.cheque_number_div').removeClass("d-none");
                $('#cheque_number').attr("required", true);
            } else {
                $('.transaction_number_div').addClass("d-none");
                $('.cheque_number_div').addClass("d-none");
                $('#transaction_number').attr("required", false);
                $('#cheque_number').attr("required", false);
            }

            $('#payment_type').on('change', function() {
                if (($(this).val() == 'Bank') || ($(this).val() == 'Card')) {
                    $('.transaction_number_div').removeClass("d-none");
                    $('#transaction_number').attr("required", true);
                    $('.cheque_number_div').addClass("d-none");
                    $('#cheque_number').attr("required", false);
                } else if ($(this).val() == 'Cheque') {
                    $('.transaction_number_div').addClass("d-none");
                    $('#transaction_number').attr("required", false);
                    $('.cheque_number_div').removeClass("d-none");
                    $('#cheque_number').attr("required", true);
                } else {
                    $('.transaction_number_div').addClass("d-none");
                    $('.cheque_number_div').addClass("d-none");
                    $('#transaction_number').attr("required", false);
                    $('#cheque_number').attr("required", false);
                }
            });
        });
    </script>
@endsection
