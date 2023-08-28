@extends('auth.layouts')

@section('title', 'Forgot Your Password? Reset It Here')

@section('content')
    <p class="login-box-msg">Reset Your Password</p>

    @include('partials.messages')

    <form action="{{ route('admin.password.forgot') }}" method="post">
        @csrf
        <div class="mb-3">
            <div class="input-group">
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="Email" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            @error('email')
                <label for="email" class="font-weight-normal text-danger small mb-0">
                    {{ $message }}
                </label>
            @enderror
        </div>
        <p class="mb-0">
            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
        </p>
        <p class="text-center mt-2 mb-0">
            <a href="{{ route('admin.login') }}">Back to Login</a>
        </p>
    </form>
@endsection
