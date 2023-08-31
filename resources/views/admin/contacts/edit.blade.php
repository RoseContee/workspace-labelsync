@extends('admin.layouts')

@section('title', 'Update Contact')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Update Contact</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Contacts</a></li>
                            <li class="breadcrumb-item active">Update</li>
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

                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('admin.contacts.update', $contact['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="mb-0">Name:</label>
                                        <span>{{ $contact['name'] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-0">Email:</label>
                                        <span>{{ $contact['email'] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-0">Subject:</label>
                                        <span>{{ $contact['subject'] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-0">Message:</label>
                                        <div class="pl-2">{{ $contact['message'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reply">Reply</label>
                                        <select id="reply" name="reply"
                                                class="custom-select @error('reply') is-invalid @enderror">
                                            <option value="0" @if (!old('reply', $contact['replied'] ?? 1)) selected @endif>
                                                Not Replied
                                            </option>
                                            <option value="1" @if (old('reply', $contact['replied'] ?? 1)) selected @endif>
                                                Replied
                                            </option>
                                        </select>
                                        @error('reply')
                                            <label for="status" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-danger ml-2">Cancel</a>
                                </div>
                            </div>
                            <!-- /.card -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
