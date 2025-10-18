@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">
        {{-- 2FA Information Card --}}
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Two-Factor Authentication')</h5>
                    
                    <div class="alert alert-success" role="alert">
                        <i class="las la-shield-alt"></i>
                        <strong>@lang('Security Status:')</strong> 
                        @if (gs()->modules->otp_email)
                            @lang('Email OTP is enabled for all transfers and important actions.')
                        @else
                            @lang('Two-factor authentication is currently disabled by admin.')
                        @endif
                    </div>
                    
                    <div class="alert alert-info" role="alert">
                        <i class="las la-info-circle"></i>
                        <strong>@lang('How it works:')</strong> 
                        <p class="mb-2 mt-2">@lang('When you make transfers or perform important actions, a verification code will be sent to your email address. Simply enter the code to confirm your action.')</p>
                        
                        <p class="mb-0"><strong>@lang('Optional:')</strong> @lang('You can also enable Google Authenticator below for additional security using time-based codes from the Google Authenticator mobile app.')</p>
                    </div>
                </div>
            </div>
        </div>

        @if (!auth()->user()->ts)
            <div class="col-md-6">
                <div class="card custom--card">

                    <div class="card-body">
                        <h5 class="card-title text-center">@lang('Setup Google Authenticator')</h5>
                        <p class="my-3 mb-3">
                            @lang('Use the QR code or setup key on your Google Authenticator app to add your account.')
                        </p>

                        <div class="form-group mx-auto text-center">
                            <img class="mx-auto" src="{{ $qrCodeUrl }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Setup Key')</label>
                            <div class="input-group">
                                <input class="form--control referralURL" name="key" type="text" value="{{ $secret }}" readonly>
                                <button class="input-group-text copytext" id="copyBoard" type="button"> <i class="fa fa-copy"></i> </button>
                            </div>
                        </div>

                        <label><i class="fa fa-info-circle"></i> @lang('Help')</label>
                        <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a class="text--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('Download')</a></p>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">

            @if (auth()->user()->ts)
                <div class="card custom--card">
                    <div class="card-body">
                        <h5 class="card-title text-center">@lang('Disable Google Authenticator')</h5>
                        <p class="text-muted text-center">@lang('Enter your Google Authenticator code to disable')</p>
                        <form action="{{ route('user.twofactor.disable') }}" method="POST">
                            @csrf
                            <input name="key" type="hidden" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticator OTP')</label>
                                <input class="form--control" name="code" type="text" required>
                            </div>
                            <button class="btn btn-md btn--base w-100" type="submit">@lang('Disable')</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card custom--card">
                    <div class="card-body">
                        <h5 class="card-title text-center">@lang('Enable Google Authenticator')</h5>
                        <p class="text-muted text-center">@lang('Scan the QR code and enter the code from your app')</p>
                        <form action="{{ route('user.twofactor.enable') }}" method="POST">
                            @csrf
                            <input name="key" type="hidden" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticator OTP')</label>
                                <input class="form--control" name="code" type="text" required>
                            </div>
                            <button class="btn btn-md btn--base w-100" type="submit">@lang('Enable')</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ gs()->base_color }};
        }

        .form--control[readonly] {
            background-color: #ffffff;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').click(function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush

@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    <li><a class="active" href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket.index') }}">@lang('Support Tickets')</a></li>
@endpush
