{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> --}}

@extends('layouts.app')
@section('title', 'Update User')

@section('styles')
    <style>
        .user-profile-header {
            margin-top: 2rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">User Profile /</span> Profile</h4>
    </div>

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto" id="preview">
                        <img src="{{ asset('uploads/user-profile/' . Auth::user()->picture) }}" alt="user image" height="100"
                            width="100" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ Auth::user()->first_name }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-12">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Profile update successfully!!!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="POST" enctype="multipart/form-data" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <input type="hidden" name="id" value="{{ $user->id }}">

                                            <div class="input-field col-sm-12 col-md-6">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <input type="text" class="form-control" name="first_name"
                                                        id="first_name"
                                                        value="{{ old('first_name', $user->first_name) }}" />
                                                    <label for="first_name">First Name</label>
                                                    @error('first_name')
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-field col-sm-12 col-md-6">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <input type="text" class="form-control" name="middle_name"
                                                        id="middle_name"
                                                        value="{{ old('middle_name', $user->middle_name) }}" />
                                                    <label for="middle_name">Middle name</label>
                                                    @error('middle_name')
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-field col-sm-12 col-md-6">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <input type="text" class="form-control" name="last_name"
                                                        id="last_name" value="{{ old('last_name', $user->last_name) }}" />
                                                    <label for="last_name">Last Name</label>
                                                    @error('last_name')
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-field col-sm-12 col-md-6">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <input type="email" class="form-control" name="email" id="email"
                                                        value="{{ old('email', $user->email) }}" readonly />
                                                    <label for="email">Email</label>
                                                    @error('email')
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-field col-sm-12 col-md-6">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <input type="text" class="form-control" name="mobile" id="mobile"
                                                        value="{{ old('mobile', $user->mobile) }}" readonly />
                                                    <label for="mobile">Mobile Number</label>
                                                    @error('mobile')
                                                        <small class="red-text ml-10" role="alert">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-field col-sm-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="file" class="form-control" name="picture"
                                                        id="bs-validation-upload-file" onchange="getImagePreview(event)">
                                                    <label for="bs-validation-upload-file">Profile pic</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary">Submit<i
                                        class="material-icons right"></i></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!--/ About User -->
        </div>
    </div>
    <!--/ User Profile Content -->
@endsection

@section('scripts')
    <script type="text/javascript">
        function getImagePreview(event) {
            var image = URL.createObjectURL(event.target.files[0]);
            var imagediv = document.getElementById('preview');
            var newimg = document.createElement('img');
            imagediv.innerHTML = '';
            newimg.src = image;
            newimg.width = 120;
            newimg.height = 128;
            imagediv.appendChild(newimg);
        }

        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
