@extends('layouts.app')
@section('title', 'Dashboard')
@section('styles')
    <style>
        .dashboard-icon::before {
            font-size: 50px !important;
        }

        .spinner-border.spinner-border-lg {
            /* position: absolute; */
            top: 0;
            bottom: 0;
            margin: auto;
            display: flex;
        }

        .overall-summary-wrapper {
            height: 450px;
        }
    </style>
@endsection
@section('content')
    {{-- <div class="container-xxl flex-grow-1 container-p-y"> --}}
    <div class="row gy-4">
        <!-- Gamification Card -->
        <div class="col-md-12 col-lg-12">
            <div class="row">
                <div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="row">
                            <div class="col-9">
                                <div class="card-body pb-0">
                                    <div class="card-info mb-3">
                                        <h4 class="mb-3 pb-lg-2" style="color: #8C0000 !important;">Total No. of
                                            Houses</h4>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0 me-2">{{ $data['total_house'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-primary" style="height: 131px;">
                                    <div class="card-body align-items-center d-flex justify-content-center">
                                        <h4 class="card-title text-white mb-0"><i
                                                class="mdi mdi-receipt-text dashboard-icon"></i></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="row">
                            <div class="col-9">
                                <div class="card-body pb-0">
                                    <div class="card-info mb-3">
                                        <h4 class="mb-3 pb-lg-2" style="color: #8C0000 !important;">Total No. of
                                            Members</h4>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0 me-2">{{ $data['total_member'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-primary" style="height: 131px;">
                                    <div class="card-body align-items-center d-flex justify-content-center">
                                        <h4 class="card-title text-white mb-0"><i
                                                class="mdi mdi-briefcase-account-outline dashboard-icon"></i></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="row">
                            <div class="col-9">
                                <div class="card-body pb-0">
                                    <div class="card-info mb-3">
                                        <h4 class="mb-3 pb-lg-2" style="color: #8C0000 !important;">Total Amount of
                                            Income</h4>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0 me-2">{{ $data['total_income_amount'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-primary" style="height: 131px;">
                                    <div class="card-body align-items-center d-flex justify-content-center">
                                        <h4 class="card-title text-white mb-0">
                                            <i class="mdi mdi-currency-rupee dashboard-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-4 mb-lg-0 mt-4">
                    <div class="card">
                        <div class="row">
                            <div class="col-9">
                                <div class="card-body pb-0">
                                    <div class="card-info mb-3">
                                        <h4 class="mb-3 pb-lg-2" style="color: #8C0000 !important;">Total Amount of
                                            Expense</h4>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <h4 class="mb-0 me-2">{{ $data['total_expense_amount'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-primary" style="height: 131px;">
                                    <div class="card-body align-items-center d-flex justify-content-center">
                                        <h4 class="card-title text-white mb-0">
                                            <i class="mdi mdi-currency-rupee dashboard-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Gamification Card -->
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 ">
            <div class="card overall-summary-wrapper">
                <div class="card-body">
                    <div class="row align-items-center h-100">
                        <div class="col-12">
                            <div id="MonthlyAmountChart"></div>
                            <div class="spinner-border spinner-border-lg text-primary"role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($) {
            $.get("get-dashboard-graph-data", function(data, textStatus, jqXHR) {
                $('.spinner-border').hide();

                var expenseData = data.monthlyData.expense;
                var incomeData = data.monthlyData.income;

                var allMonths = new Set();
                var expenseMap = {};
                var incomeMap = {};

                // Populate maps and allMonths set
                expenseData.forEach(function(item) {
                    expenseMap[item.month] = item.amount;
                    allMonths.add(item.month);
                });

                incomeData.forEach(function(item) {
                    incomeMap[item.month] = item.amount;
                    allMonths.add(item.month);
                });

                var monthOrder = ["January", "February", "March", "April", "May", "June", "July", "August",
                    "September", "October", "November", "December"
                ];

                var categories = Array.from(allMonths).sort((a, b) => monthOrder.indexOf(a) - monthOrder.indexOf(b));
                var expenseSeriesData = [];
                var incomeSeriesData = [];
                
                categories.forEach(function(month) {
                    expenseSeriesData.push(expenseMap[month] || 0);
                    incomeSeriesData.push(incomeMap[month] || 0);
                });
                Highcharts.chart('MonthlyAmountChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Expense & Income Monthly Chart',
                        align: 'center'
                    },
                    xAxis: {
                        categories: categories,
                        crosshair: true
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Amount'
                        }
                    },
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        borderWidth: 0
                    },
                    tooltip: {
                        shared: false,
                        formatter: function() {
                            var point = this.point;
                            return '<span><b>' + point.category + '</b></span><br/>' +
                                '<span>' + this.series.name + ': <b>' + point.y.toFixed(2) + '</b></span>';
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.3,
                            groupPadding: 0.2,
                            borderWidth: 0,
                            maxPointWidth: 50
                        },
                    },
                    series: [{
                        name: 'Expense',
                        data: expenseSeriesData,
                        color: '#DA5438'
                    }, {
                        name: 'Income',
                        data: incomeSeriesData,
                        color: '#7cb5ec'
                    }]
                });
            });

        });
    </script>
@endsection
