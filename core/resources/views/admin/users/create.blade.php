@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <h5 class="card-title mb-4">Account Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('First Name') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                    <small class="text-muted">Only letters, numbers, dashes and underscores</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email') <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}" required>
                                    <small class="text-muted">Include country code (e.g., +1234567890)</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Country') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="country" value="{{ old('country') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Password') <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" required>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Confirm Password') <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="card-title mb-4">Address Information (Optional)</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Address')</label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('State')</label>
                                    <input type="text" class="form-control" name="state" value="{{ old('state') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Zip Code')</label>
                                    <input type="text" class="form-control" name="zip" value="{{ old('zip') }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="card-title mb-4">Account Settings</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Branch')</label>
                                    <select class="form-control" name="branch_id">
                                        <option value="0">@lang('Online/No Branch')</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Initial Balance')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="initial_balance" value="{{ old('initial_balance', 0) }}" min="0" step="0.01">
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                    <small class="text-muted">Starting balance for this account (optional)</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="las la-info-circle"></i>
                            <strong>Note:</strong> Account will be created with active status, verified email and mobile. The user can login immediately with the provided credentials.
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">
                            <i class="las la-user-plus"></i> @lang('Create Account')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.users.all') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-arrow-left"></i> @lang('Back')
    </a>
@endpush
