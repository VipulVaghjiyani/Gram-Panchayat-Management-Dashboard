@extends('layouts.app')
@section('title', 'Role Listing')
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
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Roles</h5>
            <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="mdi mdi-plus me-sm-1"></i><span
                class="d-none d-sm-inline-block">Create Role</span></a>
        </div>
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered" id="role_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Action</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Date</th>
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

            fill_datatable();

            $("#overlay").show();
            function fill_datatable(name = '', id = '', created_at = '') {
                var dataTable = $('#role_table').DataTable({
                    searching: true,
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    lengthMenu: [10, 25, 50, 100, 1000, 10000],
                    ajax: {
                        url: "{{ route('roles.index') }}",
                    },
                    columns: [
                        {
                            data: 'id'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
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
            }, 3000);

        });
        function deleteRole(id) {
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
                        url: 'roles/delete/' + id,
                        type: "get"
                    }).done(function(data) {
                        var table = $('#role_table').DataTable();
                        table.ajax.reload();
                        if (!data.status) {
                            Swal.fire({
                            icon: 'error',
                            title: 'Cancelled!',
                            text: 'Something wrong.',
                            customClass: {
                                confirmButton: 'btn btn-success waves-effect'
                            }
                        });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Your file has been deleted.',
                                customClass: {
                                    confirmButton: 'btn btn-success waves-effect'
                                }
                            });
                        }
                        $('#role_table').DataTable().ajax.reload();
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
