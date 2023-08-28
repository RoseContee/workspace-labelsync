@extends('home.layouts')

@section('title', 'Contacts')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Contacts</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Contacts</li>
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
                    <div class="card-body">
                        <table id="contacts" class="table table-bordered table-striped"></table>
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
            $('#contacts').DataTable({
                autoWidth: false,
                responsive: true,
                lengthMenu: [[50, 100, 500, -1], [50, 100, 500, 'All']],
                columns: [
                    { title: 'No', width: 30, searchable: false },
                    { title: 'Name' },
                    { title: 'Email' },
                    { title: 'Subject' },
                    { title: 'Message' },
                    { title: 'Read', width: 40 },
                    { title: 'Replied', width: 40 },
                    { title: 'Action', width: 40, searchable: false, orderable: false },
                ],
                data: [
                    @foreach($contacts as $index => $contact)
                    [
                        '{!! ++$index !!}',
                        '{!! $contact['name'] !!}',
                        '{!! $contact['email'] !!}',
                        '{!! $contact['subject'] !!}',
                        '{!! Str::limit($contact['message'], 200) !!}',
                        '{!! !$contact['read'] ? '<span class="badge badge-warning">Unread</span>' : '' !!}',
                        '{!! !$contact['replied'] ? '<span class="badge badge-info">Not Replied</span>' : '' !!}',
                        `<a href="{{ route('admin.contacts.edit', $contact['id']) }}" class="btn btn-sm btn-primary px-1 py-0 m-1">
                            <i class="fa fa-edit"></i>
                        </a>`
                    ],
                    @endforeach
                ]
            });
        });
    </script>
@endpush
