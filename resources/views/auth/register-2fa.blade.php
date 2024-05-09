@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Two-Factor Authentication') }}</div>

                <div class="card-body">
                    <p>{{ __('Please scan the QR code with your Google Authenticator app to enable two-factor authentication.') }}</p>

                    <div class="text-center">
                    {!! QrCode::size(100)->generate($qrcode_image); !!}
                    </div>

                    <form method="POST" action="{{ route('register.verify.2fa') }}">
                        @csrf

                        <div class="form-group row mt-4">
                            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Authentication Code') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autocomplete="off" autofocus>

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verify') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection