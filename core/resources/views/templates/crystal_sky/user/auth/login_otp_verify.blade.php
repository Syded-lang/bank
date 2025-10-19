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
                                    <p class="text-muted small"><i class="las la-envelope"></i> {{ substr($user->email ?? 's*****@***.com', 0, 2) . '*****' . substr($user->email ?? '', -10) }}</p>
                                @else
                                    <p class="text-muted">@lang('Enter the 6-digit code sent to your phone')</p>
                                @endif
                            </div>

                            <form action="{{ route('user.login.otp.submit') }}" method="POST" class="submit-form">
                                @csrf
                                
                                <div class="mb-4">
                                    <div class="verification-code-wrapper">
                                        <input type="text" 
                                               name="otp" 
                                               id="verification-code" 
                                               class="verification-input" 
                                               required 
                                               autocomplete="off" 
                                               maxlength="6" 
                                               autofocus 
                                               inputmode="numeric">
                                        <div class="boxes" id="otp-boxes">
                                            <span class="box">-</span>
                                            <span class="box">-</span>
                                            <span class="box">-</span>
                                            <span class="box">-</span>
                                            <span class="box">-</span>
                                            <span class="box">-</span>
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
        
        .verification-code-wrapper {
            position: relative;
            margin: 0 auto;
            max-width: 450px;
            height: 70px;
        }
        
        .verification-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 2;
            cursor: pointer;
        }
        
        .boxes {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            height: 100%;
            z-index: 1;
        }
        
        .box {
            flex: 1;
            max-width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: #2b388f;
            background: white;
            border: 2px solid #d0d5dd;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .box.filled {
            border-color: #2b388f;
            background: #f8f9ff;
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
            
            var input = $('#verification-code');
            var boxes = $('.box');
            
            // Auto-focus on input
            input.focus();
            
            // Handle OTP input
            input.on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, ''); // Only numbers
                
                // Limit to 6 digits
                if (value.length > 6) {
                    value = value.substring(0, 6);
                }
                $(this).val(value);
                
                // Update visual boxes
                boxes.each(function(index) {
                    if (index < value.length) {
                        $(this).text('●').addClass('filled');
                    } else {
                        $(this).text('-').removeClass('filled');
                    }
                });
                
                // Auto-submit when 6 digits entered
                if (value.length === 6) {
                    $('#submit-btn').html('<i class="las la-spinner fa-spin"></i> @lang("Verifying...")');
                    setTimeout(function() {
                        $('.submit-form').submit();
                    }, 300);
                }
            });
            
            // Only allow numbers
            input.on('keypress', function(e) {
                var charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            });
            
            // Prevent paste of non-numeric content
            input.on('paste', function(e) {
                setTimeout(function() {
                    var value = input.val().replace(/[^0-9]/g, '');
                    input.val(value).trigger('input');
                }, 10);
            });
            
        })(jQuery);
    </script>
@endpush

