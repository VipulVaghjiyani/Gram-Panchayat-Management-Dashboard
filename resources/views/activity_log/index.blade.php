@extends('layouts.app')
@section('title', 'Activity Log')
@section('styles')
    <style>
        .modal-content{
            overflow-x: auto;
        }
    </style>
@endsection
@section('content')
<div class="card mb-3 filter-card">
    <div class="card-body">
        <form id="filter_form">
            <div class="row">
                <div class="col-xl-2 col-md-4 col-12 mb-4 mb-md-0">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="from" name="from" />
                        <label for="from">From</label>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-12  mb-4 mb-md-0">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="to" name="to" />
                        <label for="to">To</label>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-12  mb-4 mb-xl-0">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select select2" id="log_name" name="log_name" data-placeholder="Select Module"
                            aria-label="Default select example">
                            <option value="" selected>Select Module</option>
                            @foreach ($modules as $module)
                                <option value="{{ $module }}" @if (old('log_name') == $module) selected @endif>{{ $module }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-12 form-btn-last d-flex" style="column-gap: 20px;">
                    <button class="btn btn-primary btn-filter" type="button" name="filter" id="filter">Filter</button>
                    <button class="btn btn-primary btn-filter" type="button" name="reset" id="reset">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Activity Log</h5>
        </div>
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered" id="logLable">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sr No.</th>
                            <th>DateTime</th>
                            <th>Action</th>
                            <th>Module Name</th>
                            <th>User Name</th>
                            <th>Changed Value</th>
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

            $('#filter').click(function() {
                var from = $('#from').val();
                var to = $('#to').val();
                var log_name = $('#log_name').val();
                $('#logLable').DataTable().destroy();
                fill_datatable(from, to, log_name);
            });

            $('#reset').click(function() {
                $('#from').val('');
                $('#to').val('');
                $('#log_name').val('').trigger('change');;
                fill_datatable();
            });

            fill_datatable();

            $("#overlay").show();
            function fill_datatable(from = '', to = '', log_name = '') {
                if ($.fn.dataTable.isDataTable('#logLable')) {
                    dataTable.destroy();
                }
                dataTable = $('#logLable').DataTable({
                    searching: true,
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    lengthMenu: [10, 25, 50, 100, 1000, 10000],
                    ajax: {
                        url: "{{ route('activity-log.index') }}",
                        "data": {
                            "from": from,
                            "to": to,
                            "log_name": log_name
                        }
                    },
                    columns: [{
                            data: 'id'
                        }, {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'log_name',
                            name: 'log_name'
                        },
                        {
                            data: 'causer_id',
                            name: 'causer_id'
                        },
                        {
                            data: 'properties',
                            name: 'properties'
                        }
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
                                    return 'Details of ' + data['log_name'] + ' Activity';
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
                    },
                    fnInitComplete : function() {
                        $("#overlay").hide();
                    },
                });
            }
        });
    </script>
@endsection
