@extends('layouts.app')
@section('title', 'Petty Cash Report')

@section('styles')
    <style>
        div.dataTables_length {
            margin-left: -68px;
        }

        .paginate-info {
            font-size: 1rem;
            color: #000;
            font-weight: 500;
        }

        .responsive-table nav {
            background: none;
            height: 0;
        }

        .pagination .page-item {
            display: flex;
            align-items: center;
            padding-right: 10px;
        }

        .d-none {
            display: none;
        }

        .page-length-list {
            position: absolute;
            top: 85px;
            left: 0;
        }

        .sk-primary {
            position: absolute;
            left: 45%;
            top: 50%;
        }

        .card-datatable {
            min-height: 200px;
        }

        input[type="search"]::-webkit-search-decoration,
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-results-button,
        input[type="search"]::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }

        select#selPages {
            position: absolute;
            width: auto;
            top: 100px;
            z-index: 9;
        }

        .disabled>.page-link {
            background-color: transparent;
        }

        @media screen and (max-width:1366px) {
            .mobile-pagination {
                display: block !important;
                text-align: center;
            }

            ul.pagination {
                justify-content: center;
            }
        }

        @media screen and (max-width: 768px) {
            .mobile-pagination {
                flex-wrap: wrap;
                width: 100%;
                justify-content: center !important;
            }

            .mobile-pagination p {
                font-size: 15px;
                text-align: center;
            }

            .mobile-pagination li a {
                font-size: 13px !important;
            }

            .mobile-pagination li {
                padding-right: 0 !important;
            }

            .mobile-pagination li span {
                font-size: 13px;
                background: transparent !important;
            }

            .mobile-pagination li {
                background: transparent;
            }

            .mobile-pagination ul li.active span {
                background: #DA5438 !important;
            }

            .mobile-pagination .pagination {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            select#selPages {
                position: static;
                width: auto;
                top: 115px;
                margin: 13px auto 0;
            }
        }

        /* Overlay styles */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            display: block;
            overflow-x: hidden !important;
        }
    </style>
@endsection

@section('content')
    <div class="card mb-3 filter-card">
        <div class="card-body">
            <form id="filter_form">
                <input type="hidden" name="page_length" id="page_length" value="{{ $request->page_length }}">
                <div class="row">
                    <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="from"
                                id="from" value="{{ $request->from }}" />
                            <label for="from">From</label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" name="to"
                                id="to" value="{{ $request->to }}" />
                            <label for="to">To</label>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-12 mt-1 d-flex" style="column-gap: 20px;">
                        <button class="btn btn-primary" type="submit" id="filter" name="filter">Filter</button>
                        <button class="btn btn-primary" type="submit" name="reset" id="reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Petty Cash Report</h5>
        </div>
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <div class="row page-wrapper">
                    <div class="col-md-1">
                        <select id="selPages" class="form-select">
                            <option @if ($request->page_length == '10') selected @endif value="10">10</option>
                            <option @if ($request->page_length == '25') selected @endif value="25">25</option>
                            <option @if ($request->page_length == '50') selected @endif value="50">50</option>
                            <option @if ($request->page_length == '100') selected @endif value="100">100</option>
                            <option @if ($request->page_length == '200') selected @endif value="200">200</option>
                            <option @if ($request->page_length == '500') selected @endif value="500">500</option>
                            <option @if ($request->page_length == '1000') selected @endif value="1000">1000</option>
                            <option @if ($request->page_length == '5000') selected @endif value="5000">5000</option>
                            <option @if ($request->page_length == '10000') selected @endif value="10000">10000</option>
                        </select>
                    </div>
                </div>
                <table class="datatables-basic table table-bordered" id="income_table">
                    <!-- Overlay HTML -->
                    <div id="overlay">
                        <div class="dataTables_processing card" role="status">
                            <div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sr. No</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Description</th>
                            {{-- <th>Opening Balance</th> --}}
                            <th>Credited Amount</th>
                            <th>Debited Amount</th>
                            {{-- <th>Closing Balance</th> --}}
                            <th>Created At</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allData as $key => $item)
                            <tr>
                                <td></td>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->date ? \DateTime::createFromFormat('Y-m-d', $item->date)->format('d/m/Y') : "" }}</td>
                                <td>{{ $item->log_name }}</td>
                                <td>{{ $item->description }}</td>
                                {{-- <td>{{ $item->amount }}</td> --}}
                                <td>{{ $item->type == 'credit' ? $item->log_amount : 0 }}</td>
                                <td>{{ $item->type == 'debit' ? $item->log_amount : 0 }}</td>
                                {{-- <td>{{ $item->cash_in_hand }}</td> --}}
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->user ? $item->user->full_name : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>
                <div class="row page-wrapper">
                    <div class="col col-md-12">
                        {!! $allData->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('#overlay').css('display', 'none');
            }, 100);

            $('#from').flatpickr({
                dateFormat: 'd/m/Y'
            });

            $('#to').flatpickr({
                dateFormat: 'd/m/Y'
            });

            $(".pagination li:first-child .page-link").text("Previous");
            $(".pagination li:last-child .page-link").text("Next");

            var urlQueryParams = location.search.slice(1);
            var urlParams = new URLSearchParams(window.location.search);

            var from = urlParams.get('from') ?? "";
            var to = urlParams.get('to') ?? "";
            var memberId = urlParams.get('memberId') ?? "";
            var incomeCategoryId = urlParams.get('incomeCategoryId') ?? "";
            var paymentType = urlParams.get('paymentType') ?? "";
            var search = urlParams.get('search') ?? "";

            $('.page-item a.page-link').each(function(key, value) {
                let page = value.href.split('?')[1];
                let pageLength = urlParams.get('page_length') ? urlParams.get('page_length') : 10;
                let from = urlParams.get('from') ?? "";
                let to = urlParams.get('to') ?? "";
                let memberId = urlParams.get('memberId') ?? "";
                let incomeCategoryId = urlParams.get('incomeCategoryId') ?? "";
                let paymentType = urlParams.get('paymentType') ?? "";
                let baseUrl = value.href.split('?')[0];
                if (page || pageLength) {
                    $(this).attr('href', baseUrl + '?' + page + '&page_length=' + pageLength + '&from=' +
                        from + '&to=' + to + '&memberId=' + memberId + '&incomeCategoryId=' +
                        incomeCategoryId + '&paymentType=' + paymentType);
                } else {
                    $(this).attr('href', value.href);
                }
            });

            $('#selPages').on('change', function() {
                var pageLength = $(this).val();
                $('#page_length').val(pageLength);
                $('#filter_form').submit().trigger('click');
            });

            $('#filter').click(function() {
                fill_datatable();
            });

            $('#reset').click(function() {
                $('#from').val('');
                $('#to').val('');
                $('#memberId').val('').trigger('change');
                $('#incomeCategoryId').val('').trigger('change');
                $('#paymentType').val('').trigger('change');
                $('#income_table').DataTable().destroy();
                fill_datatable();
            });

            fill_datatable();

            $('.dataTables_filter input').on('input', function(e) {
                var inputValue = $(this).val();
                var currentPage = $('.dataTables_paginate .paginate_button.current')
                    .text(); // Get current page number

                if (inputValue.length >= 3) {
                    urlParams.set("search", inputValue);
                    urlParams.set("page", currentPage); // Set current page number in URL
                    window.location.search = urlParams.toString();
                } else if (inputValue.length === 0) {
                    urlParams.set("search", inputValue);
                    urlParams.set("page", currentPage); // Set current page number in URL
                    window.location.search = urlParams.toString();
                }
            });
            $('.dataTables_filter input').focus();

            function fill_datatable(from = '', to = '') {
                if ($.fn.dataTable.isDataTable('#income_table')) {
                    dataTable.destroy();
                }
                dataTable = $('#income_table').DataTable({
                    fixedHeader: {
                        header: true
                    },
                    "autoWidth": true,
                    deferRender: true,
                    bPaginate: false,
                    "lengthChange": false,
                    "info": false,
                    searching: true,
                    scrollX: true,
                    processing: true,
                    serverSide: false,
                    sorting: false,
                    ordering: false,
                    lengthMenu: [10, 25, 50, 100, 200, 500],

                    footerCallback: function(tfoot, data, start, end, display) {
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        var api = this.api();

                        pageTotal1 = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        pageTotal2 = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        jQuery(api.column(5).footer()).html(
                            pageTotal1.toFixed(2)
                        );

                        jQuery(api.column(6).footer()).html(
                            pageTotal2.toFixed(2)
                        );
                    },
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
                                className: 'model',
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data[4] + ' and ' + data[5];
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
                            },
                            className: 'custom-modal-class'
                        }
                    }
                });
                $('.dataTables_filter input').val(search);

            }

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 2000);

        });
    </script>
@endsection