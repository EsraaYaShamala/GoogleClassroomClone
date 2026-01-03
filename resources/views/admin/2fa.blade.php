@extends('layouts.master')
@section('title', '2FA')
@section('content')

    @if ($user->two_factor_secret && $user->two_factor_confirmed_at)
        <form action="{{ route('two-factor.disable') }}" method="POST">
            <h3>Recovery Codes</h3>
            <ul>
                @foreach ($user->recoveryCodes() as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
            @csrf
            @method('delete')
            <button class="btn btn-primary">Diable 2FA</button>
        </form>
    @else
        @if (session('status') == 'two-factor-authentication-enabled')
            <div class="mb-4 font-medium text-sm">
                Please finish configuring two factor authentication below.
            </div>
            {!! $user->twoFactorQrCodeSvg() !!}
            <form action="{{ route('two-factor.confirm') }}" method="POST">
                @csrf
                <p>Enter to confirm 2FA</p>
                <input type="text" name="code" id="code" class="form-control">
                <button class="btn btn-primary">Confirm</button>
            </form>
        @else
            <form action="{{ route('two-factor.enable') }}" method="POST">
                @csrf
                <button class="btn btn-primary">Enable 2FA</button>
            </form>
        @endif
    @endif

@endsection
