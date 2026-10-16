{{--
    Pane nhập mã OTP ngay trong modal đăng nhập/đăng ký.
    Được include trong: design_1/web/home/index.blade.php và design_1/web/layouts/app.blade.php
    Logic xử lý: public/assets/design_1/js/edtika-auth-otp.js
--}}
@php
    $otpIsEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $otpResendMinutes = (float) (getGeneralOptionsSettings('duration_of_resend_verification_code') ?? 0);
    $otpResendSeconds = $otpResendMinutes > 0 ? (int) round($otpResendMinutes * 60) : 60;
@endphp

<div class="edtika-auth-pane edtika-otp" data-auth-pane="verify" data-otp-resend-seconds="{{ $otpResendSeconds }}">
    <h3 class="edtika-auth-pane__title">{{ $otpIsEnglish ? 'Verify your account' : 'Xác minh tài khoản' }}</h3>

    <p class="edtika-otp__hint">
        {{ $otpIsEnglish ? 'We have sent a verification code to' : 'Mã xác minh đã được gửi đến' }}
        <strong data-otp-target></strong>.
        {{ $otpIsEnglish ? 'Enter the code below to finish signing up.' : 'Nhập mã bên dưới để hoàn tất đăng ký.' }}
    </p>

    <form method="POST" action="/verification" data-auth-ajax="verify" novalidate>
        @csrf
        <input type="hidden" name="username" value="email" data-otp-username>
        <input type="hidden" name="usernameValue" value="" data-otp-username-value>

        <div class="edtika-auth-field">
            <label class="edtika-auth-label">{{ $otpIsEnglish ? 'Verification code' : 'Mã xác minh' }} *</label>

            <div class="edtika-otp__boxes" data-otp-boxes>
                @foreach([1, 2, 3, 4, 5] as $otpIndex)
                    <input type="text"
                           class="edtika-otp__box"
                           name="code[{{ $otpIndex }}]"
                           inputmode="numeric"
                           pattern="[0-9]*"
                           maxlength="1"
                           autocomplete="{{ $otpIndex === 1 ? 'one-time-code' : 'off' }}"
                           aria-label="{{ ($otpIsEnglish ? 'Digit ' : 'Chữ số ') . $otpIndex }}">
                @endforeach
            </div>

            <div class="edtika-auth-error" data-auth-error="code" hidden></div>
        </div>

        <button type="submit" class="edtika-auth-submit">{{ $otpIsEnglish ? 'Verify' : 'Xác minh' }}</button>
    </form>

    <div class="edtika-auth-switch-note edtika-otp__resend">
        <span data-otp-countdown-wrap>
            {{ $otpIsEnglish ? 'You can resend the code in' : 'Bạn có thể gửi lại mã sau' }}
            <strong data-otp-countdown>01:00</strong>
        </span>

        <span data-otp-resend-wrap hidden>
            {{ $otpIsEnglish ? "Didn't receive the code?" : 'Chưa nhận được mã?' }}
            <button type="button" data-otp-resend>{{ $otpIsEnglish ? 'Resend code' : 'Gửi lại mã' }}</button>
        </span>
    </div>

    <p class="edtika-otp__note">
        {{ $otpIsEnglish ? "Can't find the email? Check your Spam or Promotions folder." : 'Không thấy email? Hãy kiểm tra cả mục Spam hoặc Quảng cáo.' }}
    </p>
</div>
