@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account">
        <div class="account-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="account-form">
                            <div class="account-form__content mb-4">
                                <h3 class="account-form__title mb-2">@lang('Verify Login')</h3>
                                @if ($authMode == '2fa')
                                    <p>@lang('Enter the 6-digit code from your Google Authenticator app')</p>
                                @elseif ($authMode == 'email')
                                    <p>@lang('Enter the 6-digit code sent to your email')</p>
                                @else
                                    <p>@lang('Enter the 6-digit code sent to your phone')</p>
                                @endif
                            </div>

                            <form action="{{ route('user.login.otp.submit') }}" method="POST" class="submit-form">
                                @csrf
                                
                                <div class="mb-3">
                                    <div class="verification-code">
                                        <input type="text" name="otp" id="verification-code" class="form--control overflow-hidden" required autocomplete="off" maxlength="6">
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

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-md btn--base w-100">@lang('Verify & Login')</button>
                                </div>

                                <div class="form-group mt-3 text-center">
                                    <a href="{{ route('user.login') }}" class="text--base">@lang('Cancel & Return to Login')</a>
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
@endpush

@push('script')
    <script>
        $('#verification-code').on('input', function() {
            $(this).val(function(i, val) {
                if (val.length >= 6) {
                    $('.submit-form').find('button[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                    $('.submit-form').submit()
                }
                if (val.length > 6) {
                    return val.substring(0, val.length - 1);
                }
                return val;
            });
            $('.boxes span').text('-');
            for (var i = 0; i < this.value.length; i++) {
                $('.boxes span').eq(i).text('*');
            }
        });
    </script>
@endpush
