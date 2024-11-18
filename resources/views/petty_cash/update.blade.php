@extends('layouts.app')
@section('title', 'Update Petty Cash')
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
            <h5 class="card-title m-0 me-2 text-secondary">Update Petty Cash</h5>
            <a href="{{ route('petty-cash.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('petty-cash.update', $data->id) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="date" id="date"
                                value="{{ old('date', date('d/m/Y', strtotime($data->date))) }}" placeholder="Enter Date"
                                required />
                            <label for="date">Date</label>
                            @error('date')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="name" value="{{ old('name', $data->name) }}"
                                id="name" placeholder="Enter Name" required />
                            <label for="name">Name</label>
                            @error('name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" name="amount"
                                value="{{ old('amount', $data->amount) }}" id="amount"
                                placeholder="Enter Opening Balance" required />
                            <label for="amount">Opening Balance</label>
                            @error('amount')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                                placeholder="Enter Description">{{ old('description', $data->description) }}</textarea>
                            <label for="description">Description</label>
                            @error('description')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title m-0 me-2 text-secondary">Add Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="petty_cash_id" value="{{ $data->id }}">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 text-end-md mb-4">
                                    <button type="button" name="add" id="add" class="btn btn-primary">Add
                                        More</button>
                                </div>
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
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>

                                            @if ($data->pettyCashLog->isNotEmpty())
                                                @foreach ($data->pettyCashLog as $pdkey => $detail)
                                                    <tr>
                                                        <td>
                                                            <input data-id="{{ $pdkey }}" type="text"
                                                                autocomplete="off" value="{{ $detail->name }}"
                                                                name="addmore[{{ $pdkey }}][name]"
                                                                class="name form-control" required />
                                                        </td>
                                                        <td>
                                                            <select data-id="{{ $pdkey }}" autocomplete="off" id="type_{{ $pdkey + 1 }}"
                                                                name="addmore[{{ $pdkey }}][type]"
                                                                class="payment-type name form-control">
                                                                <option value="">Select Payment
                                                                    Type
                                                                </option>
                                                                <option
                                                                    @if ($detail->type == 'credit') selected="selected" @endif
                                                                    value="credit">Credit</option>
                                                                <option
                                                                    @if ($detail->type == 'debit') selected="selected" @endif
                                                                    value="debit">Debit</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input data-id="{{ $pdkey }}" type="text"
                                                                autocomplete="off" value="{{ $detail->description }}"
                                                                name="addmore[{{ $pdkey }}][description]"
                                                                class="description form-control" required />
                                                        </td>
                                                        <td>
                                                            <input data-id="{{ $pdkey }}" type="text"
                                                                autocomplete="off" value="{{ $detail->amount }}"
                                                                name="addmore[{{ $pdkey }}][amount]"
                                                                class="petty_amount form-control" required />

                                                        </td>
                                                        <td><button type="button"
                                                                class="btn btn-danger remove-tr">Remove</button>
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Cash In Hand
                                                    <input type="text" autocomplete="off" name="cash_in_hand"
                                                        id="total_with_tax"
                                                        value="{{ $data->amount != '' ? $data->cash_in_hand : '' }}"
                                                        class="form-control" readonly />
                                                </td>
                                                {{-- <td></td> --}}
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
                </div>
            </div>
            {{-- <div class="card-footer text-end pt-0">
                <button type="submit" class="btn btn-primary">Open</button>
            </div> --}}
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
                    '<input type="text" autocomplete="off" name="addmore[' + i +
                    '][name]" class="form-control" required />' +
                    '</td>' +
                    '<td>' +
                    '<select style="display: block;" id="type_' + i + '" name="addmore[' + i +
                    '][type]" class="payment-type form-select" required>' +
                    '<option value="">Select Payment Type</option>' +
                    '<option value="credit">Credit</option>' +
                    '<option value="debit">Debit</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" autocomplete="off"  data-id="0" name="addmore[' + i +
                    '][description]" class="qty form-control" required />' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" autocomplete="off"  class="petty_amount form-control" data-id="' +
                    i +
                    '" name="addmore[' + i + '][amount]" class="rate form-control" required />' +
                    '</td>' +
                    '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>' +
                    '</tr>'
                );
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

            $('body').on('change', '.petty_amount', function() {
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
                    } else {
                        credit_total_amount = credit_total_amount + amount;
                    }
                });

                var cash_in_hand = '{!! isset($data->amount) != '' ? $data->amount : '0' !!}' - total_amount
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
