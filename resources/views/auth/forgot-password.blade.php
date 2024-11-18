{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('layouts.guest')
@section('styles')
@endsection
@section('content')
    <div class="card p-2">
        <div class="app-brand justify-content-center mt-2">
            <h3> <b>SBGVSSPPY</b> </h3>
        </div>

        <div class="card-body pt-0">
            <p class="mb-4">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}"
                        placeholder="Enter your email or username" autofocus />
                    <label for="email">Email or Username</label>
                    @error('email')
                        <small class="red-text ml-10" role="alert" style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Email Password Reset Link</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Login -->
    <img alt="mask" src="{{ asset('assets/img/illustrations/auth-basic-login-mask-light.png') }}"
        class="authentication-image d-none d-lg-block" {{-- data-app-light-img="{{ asset('assets/img/illustrations/auth-basic-login-mask-light.png') }}"
        data-app-dark-img="{{ asset('assets/img/illustrations/auth-basic-login-mask-dark.png') }}" --}} />
@endsection
@section('scripts')
@endsection
