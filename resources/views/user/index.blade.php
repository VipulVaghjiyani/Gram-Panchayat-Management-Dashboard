@extends('layouts.app')
@section('title', 'User Listing')

@section('styles')
    <style>
        #overlay {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0px;
            left: 0px;
            z-index: 99999;
            background-color: #000;
            filter: alpha(opacity=75);
            -moz-opacity: 0.75;
            opacity: 0.3;
            border-radius: 10px;
            display: none;
        }
        .dataTables_processing {
            z-index: 99999;
        }
    </style>
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
            <h5 class="card-title m-0 me-2 text-primary">User Listing</h5>
            @php
                $chk = \App\Models\Permission::checkCRUDPermissionToUser('User', 'create');
                if ($chk) {
                    echo '<a href="' . route('user.create') . '" class="btn btn-primary"><i class="mdi mdi-plus me-sm-1"></i><span
                    class="d-none d-sm-inline-block">Create User Record</span></a>';
                }
            @endphp
            {{-- <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="mdi mdi-plus me-sm-1"></i><span
                    class="d-none d-sm-inline-block">Create User Record</span></a> --}}
        </div>

        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered" id="user_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Action</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>Created At</th>
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

            $("#overlay").show();

            function fill_datatable(from = '', to = '') {
                var dataTable = $('#user_table').DataTable({
                    searching: true,
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    lengthMenu: [10, 25, 50, 100, 1000, 10000],
                    ajax: {
                        url: "{{ route('user.index') }}",
                        data: {
                            from: from,
                            to: to,
                        }
                    },
                    columns: [{
                            data: '',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'mobile',
                            name: 'mobile'
                        },
                        {
                            data: 'role_id',
                            name: 'role_id'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
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
                    },
                    fnInitComplete : function() {
                        $("#overlay").hide();
                    },
                });
            }

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 2000);

            $('#filter').click(function() {
                var from = $('#from').val();
                var to = $('#to').val();
                $('#user_table').DataTable().destroy();
                fill_datatable(from, to);
            });

            $('#reset').click(function() {
                $('#from').val('');
                $('#to').val('');
                $('#user_table').DataTable().destroy();
                fill_datatable();
            });
        });

        function openDropdown(id) {
            $('.dropdown-trigger-' + id).dropdown();
            $('.dropdown-trigger-' + id).dropdown('open');
        }

        function view(id) {
            window.location = "user/view/" + id;
        }

        function edit(id) {
            window.location = "user/edit/" + id;
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to Delete.",
                icon: 'warning',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, Please!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-outline-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: 'user/delete/' + id,
                        type: "get"
                    }).done(function(data) {
                        var table = $('#user_table').DataTable();
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Your file has been deleted.',
                            customClass: {
                                confirmButton: 'btn btn-success waves-effect'
                            }
                        });
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
        }
    </script>
@endsection
