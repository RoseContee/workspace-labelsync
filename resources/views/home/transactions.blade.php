@extends('home.layouts')

@section('title', 'Transactions')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ !$status ? 'All' : ucfirst($status) }} Transactions</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Transactions</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">

                @include('partials.messages')

                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-primary m-1">
                            <i class="fa fa-filter"></i> All
                        </a>
                        <a href="{{ route('admin.transactions.index', ['status' => 'completed']) }}" class="btn btn-sm btn-success m-1">
                            <i class="fa fa-filter"></i> Completed
                        </a>
                        <a href="{{ route('admin.transactions.index', ['status' => 'canceled']) }}" class="btn btn-sm btn-danger m-1">
                            <i class="fa fa-filter"></i> Canceled
                        </a>
                        <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning m-1">
                            <i class="fa fa-filter"></i> Pending
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="transactions" class="table table-bordered table-striped"></table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <script type="text/javascript">
        $(() => {
            $('#transactions').DataTable({
                autoWidth: false,
                responsive: true,
                lengthMenu: [[50, 100, 500, -1], [50, 100, 500, 'All']],
                columns: [
                    { title: 'No', width: 30, searchable: false },
                    { title: 'Transaction ID' },
                    { title: 'Email' },
                    { title: 'Amount' },
                    { title: 'Period' },
                    { title: 'Membership' },
                    { title: 'Start At' },
                    { title: 'End At' },
                    { title: 'Subscription', width: 40 },
                    { title: 'Status', width: 40 },
                ],
                data: [
                    @foreach($transactions as $index => $transaction)
                    [
                        '{!! ++$index !!}',
                        '{!! $transaction['transaction_id'] !!}',
                        '{!! $transaction['email'] !!}',
                        '{!! currency_format($transaction['amount']) !!}',
                        '{!! $transaction['period'].' '.$transaction['unit'] !!}',
                        '{!! $transaction['membership']['name'] ?? '' !!}',
                        dateFormat({!! strtotime($transaction['start_at']) * 1000 !!}),
                        dateFormat({!! strtotime($transaction['end_at']) * 1000 !!}),
                        '{!!
                            $transaction['type'] === 'start'
                            ? '<span class="btn btn-primary">New</span>'
                            : '<span class="btn btn-info">Recurring</span>'
                        !!}',
                        '{!!
                            $transaction['status'] === 'completed'
                            ? '<span class="btn btn-success">Completed</span>'
                            : ($transaction['status'] === 'pending'
                                ? '<span class="btn btn-warning">Pending</span>'
                                : ($transaction['status'] === 'canceled'
                                    ? '<span class="btn btn-danger">Canceled</span>'
                                    : '<span class="btn btn-danger">Expired</span>'
                                )
                            )
                        !!}',
                    ],
                    @endforeach
                ]
            });
        });
    </script>
@endpush
