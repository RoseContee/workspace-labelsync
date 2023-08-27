@extends('home.layouts')

@php
    $isAdd = empty($membership);
    $route = $isAdd ? route('memberships.store') : route('memberships.update', $membership['id']);
@endphp
@section('title', $isAdd ? 'Add Membership' : 'Edit Membership')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $isAdd ? 'Add Membership' : 'Edit Membership' }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('memberships.index') }}">Memberships</a></li>
                            <li class="breadcrumb-item active">{{ $isAdd ? 'Add' : 'Edit' }}</li>
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
                        <form action="{{ $route }}" method="POST">
                            @csrf
                            @if (!$isAdd)
                                @method('PUT')
                            @endif
                            <div class="card">
                                <div class="card-body">
                                    <div class="custom-control custom-checkbox form-group">
                                        <input type="checkbox" id="featured" name="featured"
                                               @if ($membership['featured'] ?? false) checked @endif
                                               class="custom-control-input">
                                        <label for="featured" class="custom-control-label">Featured</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name <span class="required">*</span></label>
                                        <input type="text" id="name" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $membership['name'] ?? '') }}"
                                               placeholder="Membership Name" required>
                                        @error('name')
                                            <label for="name" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price({{ currency() }}) <span class="required">*</span></label>
                                        <input type="number" id="price" name="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price', $membership['price'] ?? '') }}"
                                               placeholder="Membership Price" required>
                                        @error('price')
                                            <label for="price" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="origin_price">Origin Price({{ currency() }})</label>
                                        <input type="number" id="origin_price" name="origin_price"
                                               class="form-control @error('origin_price') is-invalid @enderror"
                                               value="{{ old('origin_price', $membership['origin_price'] ?? '') }}"
                                               placeholder="Membership Origin Price">
                                        @error('origin_price')
                                            <label for="origin_price" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="period">Period <span class="required">*</span></label>
                                        </div>
                                        <div class="col-6 form-group">
                                            <input type="number" id="period" name="period"
                                                   class="form-control @error('period') is-invalid @enderror"
                                                   value="{{ old('period', $membership['period'] ?? '') }}"
                                                   placeholder="Membership Period" required>
                                            @error('period')
                                                <label for="period" class="text-danger small mb-0 font-weight-normal">
                                                    {{ $message }}
                                                </label>
                                            @enderror
                                        </div>
                                        <div class="col-6 form-group">
                                            <select id="unit" name="unit"
                                                    class="custom-select @error('unit') is-invalid @enderror" required>
                                                @php $old_unit = old('unit', $membership['unit'] ?? 'year'); @endphp
                                                <option value="year" @if ($old_unit === 'year') selected @endif>
                                                    Year
                                                </option>
                                                <option value="month" @if ($old_unit === 'month') selected @endif>
                                                    Month
                                                </option>
                                                <option value="day" @if ($old_unit === 'day') selected @endif>
                                                    Day
                                                </option>
                                            </select>
                                            @error('unit')
                                                <label for="unit" class="text-danger small mb-0 font-weight-normal">
                                                    {{ $message }}
                                                </label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="supported_features">Supported Features</label>
                                        <textarea id="supported_features" name="supported_features"
                                                  class="form-control @error('supported_features') is-invalid @enderror"
                                                  rows="4"
                                                  placeholder="Supported Features..."
                                        >{{ old('supported_features', $membership['supported_features'] ?? '') }}</textarea>
                                        @error('supported_features')
                                            <label for="supported_features" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="unsupported_features">Unsupported Features</label>
                                        <textarea id="unsupported_features" name="unsupported_features"
                                                  class="form-control @error('unsupported_features') is-invalid @enderror"
                                                  rows="4"
                                                  placeholder="UnSupported Features..."
                                        >{{ old('unsupported_features', $membership['unsupported_features'] ?? '') }}</textarea>
                                        @error('unsupported_features')
                                            <label for="unsupported_features" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  rows="4"
                                                  placeholder="Membership Description"
                                        >{{ old('description', $membership['description'] ?? '') }}</textarea>
                                        @error('description')
                                            <label for="description" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status"
                                                class="custom-select @error('status') is-invalid @enderror">
                                            <option value="1" @if (old('status', $membership['active'] ?? 1)) selected @endif>
                                                Active
                                            </option>
                                            <option value="0" @if (!old('status', $membership['active'] ?? 1)) selected @endif>
                                                Disable
                                            </option>
                                        </select>
                                        @error('status')
                                            <label for="status" class="text-danger small mb-0 font-weight-normal">
                                                {{ $message }}
                                            </label>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">{{ $isAdd ? 'Create' : 'Update' }}</button>
                                    <a href="{{ route('memberships.index') }}" class="btn btn-danger ml-2">Cancel</a>
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
