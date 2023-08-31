@extends('admin.layouts')

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

                @include('admin.partials.messages')

                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-primary m-1">
                            <i class="fa fa-filter"></i> All
                        </a>
                        <a href="{{ route('admin.transactions.index', ['status' => 'completed']) }}" class="btn btn-sm btn-success m-1">
                            <i class="fa fa-filter"></i> Completed
                        </a>
                        <a href="{{ route('admin.transactions.index', ['status' => 'declined']) }}" class="btn btn-sm btn-danger m-1">
                            <i class="fa fa-filter"></i> Declined
                        </a>
                        <a href="{{ route('admin.transactions.index', ['status' => 'other']) }}" class="btn btn-sm btn-warning m-1">
                            <i class="fa fa-filter"></i> Other
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
                    { title: 'Subscription ID' },
                    { title: 'Payment Method' },
                    { title: 'Plan' },
                    { title: 'Email' },
                    { title: 'Amount' },
                    { title: 'Period' },
                    { title: 'Date' },
                    { title: 'Status', width: 40 },
                ],
                data: [
                    @foreach($transactions as $index => $transaction)
                    @php
                        $end_at = $transaction['end_at'];
                        $status = $transaction['status'];
                    @endphp
                    [
                        '{!! ++$index !!}',
                        '{!! $transaction['transaction_id'] !!}',
                        '{!! $transaction['subscription_id'] !!}',
                        '{!! $transaction['payment_method'] !!}',
                        '{!! $transaction['membership']['name'] ?? '' !!}',
                        '{!! $transaction['email'] !!}',
                        '{!! $transaction['amount'].' '.$transaction['currency'] !!}',
                        '{!! $transaction['period'].' '.$transaction['unit'] !!}',
                        dateFormat({!! strtotime($transaction['start_at']) * 1000 !!}) +
                        @if ($end_at) ' - ' + dateFormat({!! strtotime($end_at) * 1000 !!}) @else '' @endif,
                        '{!!
                            $status === 'completed'
                            ? '<span class="badge badge-success">Completed</span>'
                            : (in_array($status, ['declined', 'cancelled'])
                                ? '<span class="badge badge-danger">'.ucfirst($status).'</span>'
                                : '<span class="badge badge-warning">'.ucfirst($status).'</span>'
                            )
                        !!}',
                    ],
                    @endforeach
                ]
            });
        });
    </script>
@endpush
