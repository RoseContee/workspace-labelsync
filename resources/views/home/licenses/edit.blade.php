@extends('home.layouts')

@section('title', 'Edit License')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit License</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.licenses.index') }}">Licenses</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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

                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('admin.licenses.update', $license['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="timezoneOffset" value="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="form-control" readonly>{{ $license['email'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label>License Key</label>
                                        <div class="form-control" readonly>{{ $license['key'] }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="key_update">Key Update</label>
                                        <select id="key_update" name="key_update" class="custom-select">
                                            <option value="0">No</option>
                                            <option value="1">New Generate</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="expires_on">Expires On <span class="required">*</span></label>
                                        <input type="text" id="expires_on" name="expires_on" autocomplete="off"
                                               class="form-control datetimepicker-input @error('expires_on') is-invalid @enderror"
                                               data-target="#expires_on" data-toggle="datetimepicker"
                                               placeholder="Expires On" required>
                                        @error('expires_on')
                                            <label for="expires_on" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status"
                                                class="custom-select @error('status') is-invalid @enderror">
                                            <option value="1" @if (old('status', $license['active'])) selected @endif>
                                                Active
                                            </option>
                                            <option value="0" @if (!old('status', $license['active'])) selected @endif>
                                                Disable
                                            </option>
                                        </select>
                                        @error('status')
                                            <label for="status" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <textarea id="note" name="note"
                                                  class="form-control @error('note') is-invalid @enderror"
                                                  placeholder="Note...">{{ old('note', $license['note']) }}</textarea>
                                    </div>
                                    @if (empty($license['transaction']))
                                        <div class="form-group">
                                            <label for="transaction_id">Transaction ID</label>
                                            <input type="text" id="transaction_id" name="transaction_id"
                                                   class="form-control @error('transaction_id') is-invalid @enderror"
                                                   value="{{ old('transaction_id', $license['transaction_id']) }}"
                                                   placeholder="Transaction...">
                                        </div>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('admin.licenses.index') }}" class="btn btn-danger ml-2">Cancel</a>
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

@push('scripts')
    <script type="text/javascript">
        $(() => {
            @php
                $default_expires_on = strtotime($license['expires_on']) * 1000;
                if ($expires_on = old('expires_on')) $default_expires_on = $expires_on;
            @endphp
            //Date and time picker
            $('#expires_on').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                icons: { time: 'far fa-clock' },
                defaultDate: new Date({!! $expires_on ? "'$default_expires_on'" : $default_expires_on  !!}),
            });
        });
    </script>
@endpush
