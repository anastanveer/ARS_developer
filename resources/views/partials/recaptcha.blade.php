@if(config('recaptcha.enabled') && filled(config('recaptcha.site_key')))
    <div class="ars-recaptcha-box">
        <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
        @error('g-recaptcha-response')
            <small class="ars-recaptcha-box__error">{{ $message }}</small>
        @enderror
    </div>
@endif
