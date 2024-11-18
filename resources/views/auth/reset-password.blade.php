{{-- <x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
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
            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.store') }}">
                @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" id="email" name="email" value="{{old('email', $request->email)}}"
                        placeholder="Enter your email or username" autofocus />
                    <label for="email">Email or Username</label>
                    @error('email')
                        <small class="red-text ml-10" role="alert" style="color: red">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: red" />
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password_confirmation" />
                                <label for="password_confirmation">Confirm Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: red"  />
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
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
