@extends('layouts.app')
@section('title', 'Create Petty Cash')
@section('styles')
    <style>
        .red-text {
            color: red;
        }

        @media screen and (max-width: 1440px){
            .table-responsive {
                overflow: scroll;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Add Item details</h5>
            <a href="{{ route('petty-cash.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('petty-cash-log.store', $data->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="petty_cash_id" value="{{ $data->id }}">
                    <div class="row">
                        @php
                            $pettyCashLogCount = 0;
                            if ($data->pettyCashLog->isNotEmpty()) {
                                if ($data->pettyCashLog->count() > 0) {
                                    $pettyCashLogCount = $data->pettyCashLog->count();
                                }
                            }
                        @endphp
                        <input type="hidden" value="{{ $pettyCashLogCount }}" id="cash_log_count">
                        <div class="table-responsive text-nowrap">
                            <table class="table" id="dynamicT">
                                <thead>
                                    <tr>
                                        <td>Opening Balance
                                            <input type="text" autocomplete="off" id="opening_amount"
                                                value="{{ $data->opening_balance }}" class="form-control" readonly /></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>

                                    @if ($data->pettyCashLog->isNotEmpty())
                                        @foreach ($data->pettyCashLog as $pdkey => $detail)
                                            <tr>
                                                <input name="addmore[<?= $pdkey ?>][id]" value="{{ $detail->id }}"
                                                    type="hidden">
                                                <td>
                                                    <input data-id="{{ $pdkey }}" type="number" autocomplete="off"
                                                        value="{{ $detail->amount }}"
                                                        name="addmore[{{ $pdkey }}][amount]"
                                                        class="petty_amount form-control" />
                                                </td>
                                                <td>
                                                    <input data-id="{{ $pdkey }}" type="text" autocomplete="off"
                                                        value="{{ $detail->name }}"
                                                        name="addmore[{{ $pdkey }}][name]"
                                                        class="name form-control" />
                                                </td>
                                                <td>
                                                    <select data-id="{{ $pdkey }}" id="type_{{ $pdkey + 1 }}"
                                                        autocomplete="off" name="addmore[{{ $pdkey }}][type]"
                                                        class="payment-type name form-select select2">
                                                        <option value="">Select Payment
                                                            Type
                                                        </option>
                                                        <option @if ($detail->type == 'credit') selected="selected" @endif
                                                            value="credit">Credit</option>
                                                        <option @if ($detail->type == 'debit') selected="selected" @endif
                                                            value="debit">Debit</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input data-id="{{ $pdkey }}" type="text" autocomplete="off"
                                                        value="{{ $detail->description }}"
                                                        name="addmore[{{ $pdkey }}][description]"
                                                        class="description form-control" />
                                                </td>
                                                <td>
                                                    {{-- @if ($pdkey != '0') --}}
                                                        <button type="button" data-model="petty"
                                                            data-id="{{ $detail->id }}"
                                                            class="btn btn-danger remove-tr">Remove</button>
                                                    {{-- @endif --}}

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <button type="button" name="add" id="add" class="btn btn-primary">Add
                                                More</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{-- Opening Balance
                                            <input type="text" autocomplete="off" id="opening_amount"
                                                value="{{ $data->opening_balance }}" class="form-control" readonly /> --}}
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td>Cash In Hand
                                            <input type="text" autocomplete="off" name="cash_in_hand" id="total_with_tax"
                                                value="{{ $data->opening_balance != '' ? $data->cash_in_hand : '' }}"
                                                class="form-control" readonly />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer pt-0">
                <div class="row">
                    <div class="col-6 mt-4">
                        <a href="{{ route('petty-cash.index') }}" class="btn btn-primary">Back</a>
                    </div>
                    <div class="col-6 text-end  mt-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('form').parsley();

        $('#date').flatpickr({
            dateFormat: 'd/m/Y'
        });

        $(document).ready(function() {

            var i = 0;

            $("#add").click(function() {
                i = $('#dynamicT tbody tr').length - 1;
                console.log($('#dynamicT tbody tr').length - 1);
                i++;
                $("#dynamicT").append(
                    '<tr>' +
                    '<td>' +
                    '<input type="number" autocomplete="off" class="petty_amount form-control" data-id="' +
                    i +
                    '" name="addmore[' + i + '][amount]" class="rate form-control" required />' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" autocomplete="off" name="addmore[' + i +
                    '][name]" class="form-control" required />' +
                    '</td>' +
                    '<td>' +
                    '<select style="display: block;" id="type_' + i + '" name="addmore[' + i +
                    '][type]" class="payment-type form-select select2" data-placeholder="Select Type" required data-parsley-errors-container="#type_errors">' +
                    '<option value="">Select Type</option>' +
                    '<option value="credit">Credit</option>' +
                    '<option value="debit">Debit</option>' +
                    '</select>' +
                    '<div id="type_errors"></div>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" autocomplete="off"  data-id="0" name="addmore[' + i +
                    '][description]" class="qty form-control" />' +
                    '</td>' +
                    '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>' +
                    '</tr>'

                );
                $('.select2').select2();
            });

            $(document).on('click', '.remove-tr', function() {
                if (confirm('Are you sure you want to delete this record ?')) {
                    $(this).parents('tr').remove();
                    var data = {
                        model: $(this).data('model')
                    }
                    deleteAddMoreValues($(this).data('id'), data);
                }
            });

            function deleteAddMoreValues(id, data) {
                $.get("delete-extras/" + id, data, function(data, textStatus, jqXHR) {
                    M.toast({
                        html: data.message
                    })
                });
            }

            $('form').parsley();

            $('body').on('change', '.petty_amount, .payment-type', function() {
                cashInHand();
            });

            function cashInHand() {
                var total_amount = 0;
                var credit_total_amount = 0;
                $(".petty_amount").each(function(key) {
                    var amount = $(this).val();
                    var count = key + 1;
                    var type = $("#type_" + count).val();
                    amount = parseFloat(amount);

                    if (amount != 'undefined' && !isNaN(amount) && type == "debit") {
                        total_amount = total_amount + amount;
                        console.log('total_amount', total_amount);
                    } else {
                        credit_total_amount = credit_total_amount + amount;
                    }
                });

                var cash_in_hand = '{!! isset($data->opening_balance) != '' ? $data->opening_balance : '0' !!}' - total_amount
                $('input[name="cash_in_hand"]').val(cash_in_hand + credit_total_amount);
            }

            $(document).on('click', '.remove-tr', function() {
                $(this).parents('tr').remove();
                reinitializeType();
                cashInHand();
            });

            function reinitializeType() {
                $(".payment-type").each(function(key) {
                    $(this).removeAttr('id');
                    $(this).attr('id', 'type_' + parseInt(key + 1));
                });
            }
        });
    </script>
@endsection
