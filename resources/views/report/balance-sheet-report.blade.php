@extends('layouts.app')
@section('title', 'Balance Sheet Report')

@section('styles')
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="from" placeholder="DD/MM/YYYY" class="form-control" name="from" />
                            <label for="from">From</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="to" placeholder="DD/MM/YYYY" class="form-control"
                                name="to" />
                            <label for="to">To</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                        <button type="button" name="reset" id="reset" class="btn btn-primary">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-primary">Balance Sheet Report</h5>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered" id="balance_sheet_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sr No</th>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th>Opening Balance</th>
                            <th>Credited</th>
                            <th>Debited</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#from').flatpickr({
                dateFormat: 'd/m/Y'
            });

            $('#to').flatpickr({
                dateFormat: 'd/m/Y'
            });

            fill_datatable();

            function fill_datatable(from = '', to = '') {
                var dataTable = $('#balance_sheet_table').DataTable({
                    searching: true,
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    lengthMenu: [10, 25, 50, 100, 1000, 10000],
                    ajax: {
                        url: "{{ route('report.balance-sheet-report') }}",
                        data: {
                            from: from,
                            to: to,
                        }
                    },
                    columns: [{
                            data: '',
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'account_number',
                            name: 'account_number'
                        },
                        {
                            data: 'opening_balance',
                            name: 'opening_balance'
                        },
                        {
                            data: 'credited',
                            name: 'credited'
                        },
                        {
                            data: 'debited',
                            name: 'debited'
                        },
                        {
                            data: 'balance',
                            name: 'balance'
                        },
                    ],
                    columnDefs: [{
                        // For Responsive
                        className: 'control',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return '';
                        }
                    }, ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['name'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !==
                                        '' // ? Do not show row in modal popup if title is blank (for check box)
                                        ?
                                        '<tr data-dt-row="' +
                                        col.rowIndex +
                                        '" data-dt-column="' +
                                        col.columnIndex +
                                        '">' +
                                        '<td>' +
                                        col.title +
                                        ':' +
                                        '</td> ' +
                                        '<td>' +
                                        col.data +
                                        '</td>' +
                                        '</tr>' :
                                        '';
                                }).join('');

                                return data ? $('<table class="table"/><tbody />').append(data) : false;
                            }
                        }
                    }
                });
            }

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 2000);

            $('#filter').click(function() {
                var from = $('#from').val();
                var to = $('#to').val();
                $('#balance_sheet_table').DataTable().destroy();
                fill_datatable(from, to);
            });

            $('#reset').click(function() {
                $('#from').val('');
                $('#to').val('');
                $('#balance_sheet_table').DataTable().destroy();
                fill_datatable();
            });
        });
    </script>
@endsection
