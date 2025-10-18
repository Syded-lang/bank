@if (checkIsOtpEnable())
    <div class="form-group mt-0">
        <label for="verification">@lang('Authorization Mode')</label>
        <select name="auth_mode" id="verification" class="form--control select" required>
            <option disabled value="">@lang('Select One')</option>
            @if (auth()->user()->ts)
                <option value="2fa" @selected(auth()->user()->preferred_2fa_method == 'google')>@lang('Google Authenticator')</option>
            @endif
            @if (gs()->modules->otp_email)
                <option value="email" @selected(auth()->user()->preferred_2fa_method == 'email')>@lang('Email')</option>
            @endif
            @if (gs()->modules->otp_sms)
                <option value="sms" @selected(auth()->user()->preferred_2fa_method == 'sms')>@lang('SMS')</option>
            @endif
        </select>
        <small class="text-muted">
            <i class="las la-info-circle"></i> @lang('Your preferred method is pre-selected. You can change it in 2FA Settings.')
        </small>
    </div>
@endif
