@extends('admin.layouts')

@section('title', 'Memberships')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Memberships</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Memberships</li>
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
                        <h3 class="card-title">
                            <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Membership
                            </a>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="memberships" class="table table-bordered table-striped"></table>
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

    <!-- Disable Modal -->
    <div class="modal fade" id="disable-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h4 class="modal-title">Disable Membership</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to deactivate this membership?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@push('scripts')
    <script type="text/javascript">
        const disableModal = $('#disable-modal');

        $(() => {
            disableModal.on('hide.bs.modal', () => {
                disableModal.find('form').attr('action', '');
            })
        });

        const onDisable = (id) => {
            const action = '{{ route('admin.memberships.index') }}/' + id;
            disableModal.find('form').attr('action', action);
            disableModal.modal('show');
        }
    </script>

    <script type="text/javascript">
        $(() => {
            $('#memberships').DataTable({
                autoWidth: false,
                responsive: true,
                info: false,
                lengthChange: false,
                columns: [
                    { title: 'No', width: 30, searchable: false },
                    { title: 'Name' },
                    { title: 'Price' },
                    { title: 'Features' },
                    { title: 'Plan ID' },
                    { title: 'Description' },
                    { title: 'Status', width: 40 },
                    { title: 'Action', width: 66, searchable: false, orderable: false },
                ],
                data: [
                    @foreach($memberships as $index => $membership)
                    @php
                        $price = currency_format($membership['price']);
                        $origin_price = '';
                        if ($membership['origin_price']) {
                            $origin_price = ' (<span class="origin-price">'.currency_format($membership['origin_price']).'</span>)';
                        }

                        $features = '';
                        if ($membership['supported_features']) {
                            $supported = explode("\n", $membership['supported_features']);
                            foreach ($supported as $item) {
                                if (!($text = trim($item))) continue;
                                $features .= '<li><i class="fa fa-check text-success"></i> '.$text.'</li>';
                            }
                        }
                        if ($membership['unsupported_features']) {
                            $unsupported = explode("\n", $membership['unsupported_features']);
                            foreach ($unsupported as $item) {
                                if (!($text = trim($item))) continue;
                                $features .= '<li><i class="fa fa-times text-gray"></i> <span class="unsupported-features">'.$text.'</span></li>';
                            }
                        }

                        $plans = '<p class="mb-0"><b>PayPal:</b> '.$membership['paypal_plan_id'].'</p>';

                        $status = '<span class="badge badge-danger">Disable</span>';
                        if ($membership['active']) {
                            $status = '<span class="badge badge-success">Active</span>';
                            if ($membership['featured']) {
                                $status .= '<span class="badge badge-info">Featured</span>';
                            }
                        }
                    @endphp
                    [
                        '{!! ++$index !!}',
                        '{!! $membership['name'] !!}',
                        '{!!
                            $price.$origin_price.' <span class="small">per</span> '
                            .$membership['period'].' '.$membership['unit'].'/user'
                        !!}',
                        '<ul class="list-unstyled">{!! $features !!}</ul>',
                        '{!! $plans !!}',
                        '{!! $membership['description'] !!}',
                        '{!! $status !!}',
                        `<a href="{{ route('admin.memberships.edit', $membership['id']) }}" class="btn btn-sm btn-primary px-1 py-0 m-1">
                            <i class="fa fa-edit"></i>
                        </a>`
                        @if ($membership['active'])
                            + `<button class="btn btn-sm btn-danger px-1 py-0 m-1"
                                    onClick="onDisable({{ $membership['id'] }})">
                                <i class="fa fa-ban"></i>
                            </button>`
                        @endif
                    ],
                    @endforeach
                ]
            });
        });
    </script>
@endpush
