@extends('home.layouts')

@section('title', 'Settings')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Settings</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Settings</li>
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
                        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="site_name">Site Name <span class="required">*</span></label>
                                        <input type="text" id="site_name" name="site_name"
                                               class="form-control @error('site_name') is-invalid @enderror"
                                               value="{{ old('site_name', getSiteName($settings['site_name'] ?? null)) }}"
                                               placeholder="Site Name" required>
                                        @error('site_name')
                                            <label for="site_name" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="favicon">Favicon</label>
                                        <div class="ml-2 mb-2">
                                            <img src="{{ getFavicon($settings['favicon'] ?? null) }}" alt="favicon"
                                                 style="width: 40px; height: 40px;" />
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" id="favicon" name="favicon" accept="image/*"
                                                   class="custom-file-input @error('favicon') is-invalid @enderror">
                                            <label for="favicon" class="custom-file-label">Choose file</label>
                                        </div>
                                        @error('favicon')
                                            <label for="favicon" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <div class="ml-2 mb-2">
                                            <img src="{{ getLogo($settings['logo'] ?? null) }}" alt="logo"
                                                 style="width: 200px; height: 40px;" />
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" id="logo" name="logo" accept="image/*"
                                                   class="custom-file-input @error('logo') is-invalid @enderror">
                                            <label for="logo" class="custom-file-label">Choose file</label>
                                        </div>
                                        @error('logo')
                                            <label for="logo" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_email">Contact Email <span class="required">*</span></label>
                                        <input type="text" id="contact_email" name="contact_email"
                                               class="form-control @error('contact_email') is-invalid @enderror"
                                               value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                                               placeholder="Contact Email" required>
                                        @error('contact_email')
                                            <label for="contact_email" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_phone">Contact Phone <span class="required">*</span></label>
                                        <input type="text" id="contact_phone" name="contact_phone"
                                               class="form-control @error('contact_phone') is-invalid @enderror"
                                               value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                                               placeholder="Contact Phone" required>
                                        @error('contact_phone')
                                            <label for="contact_phone" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_address">Contact Address <span class="required">*</span></label>
                                        <textarea id="contact_address" name="contact_address"
                                                  class="form-control @error('contact_address') is-invalid @enderror"
                                                  rows="4"
                                                  required>{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                                        @error('contact_address')
                                            <label for="contact_address" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="map_link">Map Link <span class="required">*</span></label>
                                        <input type="url" id="map_link" name="map_link"
                                               class="form-control @error('map_link') is-invalid @enderror"
                                               value="{{ old('map_link', $settings['map_link'] ?? '') }}"
                                               placeholder="Map Link" required>
                                        @error('map_link')
                                            <label for="map_link" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="facebook_link">Facebook Link</label>
                                        <input type="url" id="facebook_link" name="facebook_link"
                                               class="form-control @error('facebook_link') is-invalid @enderror"
                                               value="{{ old('facebook_link', $settings['facebook_link'] ?? '') }}"
                                               placeholder="Facebook Link">
                                        @error('facebook_link')
                                            <label for="facebook_link" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="skype_link">Skype Link</label>
                                        <input type="url" id="skype_link" name="skype_link"
                                               class="form-control @error('skype_link') is-invalid @enderror"
                                               value="{{ old('skype_link', $settings['skype_link'] ?? '') }}"
                                               placeholder="Skype Link">
                                        @error('skype_link')
                                            <label for="skype_link" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="linkedin_link">Linkedin Link</label>
                                        <input type="url" id="linkedin_link" name="linkedin_link"
                                               class="form-control @error('linkedin_link') is-invalid @enderror"
                                               value="{{ old('linkedin_link', $settings['linkedin_link'] ?? '') }}"
                                               placeholder="Linkedin Link">
                                        @error('linkedin_link')
                                            <label for="linkedin_link" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select id="currency" name="currency" required
                                                class="form-control @error('currency') is-invalid @enderror">
                                            <option value="€">€</option>
                                            <option value="$">$</option>
                                        </select>
                                        @error('currency')
                                            <label for="currency" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
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
    <!-- bs-custom-file-input -->
    <script src="{{ asset('assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
@endpush
