@if (checkIsOtpEnable())
    {{-- Auto-set auth_mode based on user preference, default to email --}}
    @php
        $authMode = 'email'; // Default to email
        if (auth()->user()->ts && auth()->user()->preferred_2fa_method == 'google') {
            $authMode = '2fa';
        } elseif (auth()->user()->preferred_2fa_method == 'sms' && gs()->modules->otp_sms) {
            $authMode = 'sms';
        }
    @endphp
    <input type="hidden" name="auth_mode" value="{{ $authMode }}">
    
    <div class="alert alert-info">
        <i class="las la-shield-alt"></i>
        @if ($authMode == '2fa')
            @lang('Verification code will be sent via Google Authenticator')
        @elseif ($authMode == 'sms')
            @lang('Verification code will be sent via SMS')
        @else
            @lang('Verification code will be sent to your email')
        @endif
        <br>
        <small class="text-muted">@lang('You can change your preferred method in 2FA Settings')</small>
    </div>
@endif
