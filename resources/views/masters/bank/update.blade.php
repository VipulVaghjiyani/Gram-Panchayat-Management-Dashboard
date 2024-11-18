@extends('layouts.app')
@section('title', 'Update Bank')
@section('styles')
    <style>
        .red-text {
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Update Bank</h5>
            <a href="{{ route('bank.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('bank.update', $bank->id) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="name" value="{{ old('name', $bank->name) }}"
                                id="name" placeholder="Enter Name" required />
                            <label for="name">Name</label>
                            @error('name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="account_name" value="{{ old('account_name', $bank->account_name) }}"
                                id="account_name" placeholder="Enter Account Name" required />
                            <label for="account_name">Account Name</label>
                            @error('account_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="account_number" value="{{ old('account_number', $bank->account_number) }}"
                                id="account_number" placeholder="Enter Account Number" required />
                            <label for="account_number">Account Number</label>
                            @error('account_number')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="ifcs_code" value="{{ old('ifcs_code', $bank->ifcs_code) }}"
                                id="ifcs_code" placeholder="Enter IFCS Code" required />
                            <label for="ifcs_code">IFCS Code</label>
                            @error('ifcs_code')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="branch" value="{{ old('branch', $bank->branch) }}"
                                id="branch" placeholder="Enter Branch" required />
                            <label for="branch">Branch</label>
                            @error('branch')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" name="opening_balance" value="{{ old('opening_balance', $bank->opening_balance) }}"
                                id="opening_balance" placeholder="Enter Branch" readonly />
                            <label for="opening_balance">Opening Balance</label>
                            @error('opening_balance')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
<script>
    $('form').parsley();
</script>
@endsection
