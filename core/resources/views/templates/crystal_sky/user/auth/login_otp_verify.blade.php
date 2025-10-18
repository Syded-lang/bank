@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account">
        <div class="account-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="account-form">
                            <div class="account-form__content mb-4 text-center">
                                <!-- Icon -->
                                <div class="otp-icon mb-3">
                                    <i class="las la-shield-alt" style="font-size: 64px; color: #2b388f;"></i>
                                </div>
                                
                                <h3 class="account-form__title mb-2">@lang('Verify Login')</h3>
                                
                                @if ($authMode == '2fa')
                                    <p class="text-muted">@lang('Enter the 6-digit code from your Google Authenticator app')</p>
                                @elseif ($authMode == 'email')
                                    <p class="text-muted">@lang('Enter the 6-digit code sent to your email')</p>
                                    <p class="text-muted small"><i class="las la-envelope"></i> {{ substr(auth()->user()->email ?? 's*****@***.com', 0, 2) . '*****' . substr(auth()->user()->email ?? '', -10) }}</p>
                                @else
                                    <p class="text-muted">@lang('Enter the 6-digit code sent to your phone')</p>
                                @endif
                            </div>

                            <form action="{{ route('user.login.otp.submit') }}" method="POST" class="submit-form">
                                @csrf
                                
                                <div class="mb-4">
                                    <div class="verification-code">
                                        <input type="text" name="otp" id="verification-code" class="form--control overflow-hidden" required autocomplete="off" maxlength="6" autofocus placeholder="000000">
                                        <div class="boxes">
                                            <span>-</span>
                                            <span>-</span>
                                            <span>-</span>
                                            <span>-</span>
                                            <span>-</span>
                                            <span>-</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-md btn--base w-100" id="submit-btn">
                                        <i class="las la-sign-in-alt"></i> @lang('Verify & Login')
                                    </button>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <a href="{{ route('user.login') }}" class="text-muted small">
                                        <i class="las la-arrow-left"></i> @lang('Cancel & Return to Login')
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/verification-code.css') }}">
    <style>
        .otp-icon {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }
        
        .verification-code {
            position: relative;
            margin: 0 auto;
            max-width: 400px;
        }
        
        .verification-code input {
            font-size: 24px;
            letter-spacing: 10px;
            text-align: center;
            font-weight: 600;
            padding: 15px;
        }
        
        .boxes {
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 15px 20px;
        }
        
        .boxes span {
            font-size: 28px;
            font-weight: 700;
            color: #2b388f;
            flex: 1;
            text-align: center;
        }
        
        #resend-otp {
            font-size: 14px;
            transition: all 0.3s;
        }
        
        #resend-otp:hover {
            transform: scale(1.05);
        }
        
        #submit-btn {
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            
            // Auto-focus on input
            $('#verification-code').focus();
            
            // Handle OTP input
            $('#verification-code').on('input', function() {
                $(this).val(function(i, val) {
                    // Auto-submit when 6 digits entered
                    if (val.length >= 6) {
                        $('#submit-btn').html('<i class="las la-spinner fa-spin"></i> @lang("Verifying...")');
                        setTimeout(function() {
                            $('.submit-form').submit();
                        }, 300);
                    }
                    // Limit to 6 digits
                    if (val.length > 6) {
                        return val.substring(0, 6);
                    }
                    return val;
                });
                
                // Update visual boxes
                $('.boxes span').text('-');
                for (var i = 0; i < this.value.length; i++) {
                    $('.boxes span').eq(i).text('●');
                }
            });
            
            // Only allow numbers
            $('#verification-code').on('keypress', function(e) {
                var charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            });
            
        })(jQuery);
    </script>
@endpush

