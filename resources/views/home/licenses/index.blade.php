@extends('home.layouts')

@section('title', 'Licenses')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Licenses</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Licenses</li>
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
                        <h3 class="card-title">
                            <a href="{{ route('licenses.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add License
                            </a>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="licenses" class="table table-bordered table-striped"></table>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h4 class="modal-title">Remove License</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to remove the license key for <b id="email"></b>?</p>
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
        const deleteModal = $('#delete-modal');

        $(() => {
            deleteModal.on('hide.bs.modal', () => {
                deleteModal.find('form').attr('action', '');
                deleteModal.find('#email').text('');
            });
        });

        const onDelete = (id, email) => {
            const action = '{{ route('licenses.index') }}/' + id;
            deleteModal.find('form').attr('action', action);
            deleteModal.find('#email').text(email);
            deleteModal.modal('show');
        }
    </script>

    <script type="text/javascript">
        $(() => {
            $('#licenses').DataTable({
                autoWidth: false,
                responsive: true,
                lengthMenu: [[50, 100, 500, -1], [50, 100, 500, 'All']],
                columns: [
                    { title: 'No', width: 30, searchable: false },
                    { title: 'Email' },
                    { title: 'License Key' },
                    { title: 'Status', width: 40 },
                    { title: 'Expires On' },
                    { title: 'Membership' },
                    { title: 'Transaction' },
                    { title: 'Note' },
                    { title: 'Action', width: 65, searchable: false, orderable: false },
                ],
                data: [
                    @foreach ($licenses as $index => $license)
                    [
                        '{!! ++$index !!}',
                        '{!! $license['email'] !!}',
                        '{!! $license['key'] !!}',
                        '{!!
                            $license['status'] === 'Active'
                            ? '<span class="badge badge-success">Active</span>'
                            : (($license['status'] === 'Expired')
                                ? '<span class="badge badge-warning">Expired</span>'
                                : '<span class="badge badge-danger">Disabled</span>'
                            )
                        !!}',
                        dateFormat({!! strtotime($license['expires_on']) * 1000 !!}),
                        '{!! $license['membership'] !!}',
                        '{!! $license['transaction_id'] !!}',
                        '{!! $license['note'] !!}',
                        `<a href="{{ route('licenses.edit', $license['id']) }}" class="btn btn-sm btn-primary px-1 py-0 m-1">
                            <i class="fa fa-edit"></i>
                        </a>`
                        @if (!$license['transaction'])
                            + `<button class="btn btn-sm btn-danger px-1 py-0 m-1 delete-license"
                                    onClick="onDelete('{{ $license['id'] }}', '{{ $license['email'] }}')">
                                <i class="fa fa-trash"></i>
                            </button>`
                        @endif,
                    ],
                    @endforeach
                ]
            });
        });
    </script>
@endpush
