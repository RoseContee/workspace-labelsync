@extends('admin.layouts')

@section('title', 'Dashboard')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row mb-3">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Active Users</span>
                                <span class="info-box-number">{{ $activeUsers }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users-slash"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Expired Users</span>
                                <span class="info-box-number">{{ $expiredUsers }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-money-bill"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Income</span>
                                <span class="info-box-number">{{ currency_format($totalIncome) }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">This Month</span>
                                <span class="info-box-number">{{ currency_format($monthIncome) }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Last 7 Days Sales</h3>
                                    <p class="text-bold text-lg mb-0">{{ currency_format(array_sum($weekData)) }}</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="week-sales-chart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Year Sales</h3>
                                    <p class="text-bold text-lg mb-0">{{ currency_format(array_sum($yearData)) }}</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="year-sales-chart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <!-- ChartJS -->
    <script src="{{ asset('assets/admin/plugins/chart.js/Chart.min.js') }}"></script>

    <script type="text/javascript">
        const ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        };
        const mode = 'index';
        const intersect = true;

        const chartOption = {
            maintainAspectRatio: false,
            tooltips: { mode: mode, intersect: intersect },
            hover: { mode: mode, intersect: intersect },
            legend: { display: false },
            scales: {
                yAxes: [{
                    // display: false,
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: $.extend({
                        beginAtZero: true,
                        // Include a dollar sign in the ticks
                        callback: function (value) {
                            if (value >= 1000) {
                                value /= 1000
                                value += 'k'
                            }
                            return '{{ currency() }}' + value
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    gridLines: { display: false },
                    ticks: ticksStyle
                }]
            }
        };

        new Chart($('#week-sales-chart'), {
            data: {
                labels: ['{!! implode("', '", $weekLabels) !!}'],
                datasets: [
                    {
                        type: 'line',
                        data: [{!! implode(', ', $weekData) !!}],
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                        // pointHoverBackgroundColor: '#007bff',
                        // pointHoverBorderColor    : '#007bff'
                    }
                ]
            },
            options: chartOption
        });

        new Chart($('#year-sales-chart'), {
            type: 'bar',
            data: {
                labels: ['{!! implode("', '", $yearLabels) !!}'],
                datasets: [
                    {
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        data: [{!! implode(', ', $yearData) !!}]
                    }
                ]
            },
            options: chartOption
        });
    </script>
@endpush
