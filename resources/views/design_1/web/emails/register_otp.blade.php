<!DOCTYPE html>
<html lang="{{ $isEnglish ? 'en' : 'vi' }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $isEnglish ? 'Verification code' : 'Mã xác minh' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style type="text/css">
        body, table, td, p, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        table { border-collapse: collapse; }
        .rounded { border-collapse: separate !important; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; display: block; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #F1EEFB; }
        .font { font-family: 'Be Vietnam Pro', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }

        @media screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .card-pad { padding: 28px 22px !important; }
            .col-text { display: block !important; width: 100% !important; }
            .col-img { display: block !important; width: 100% !important; text-align: center !important; padding-top: 8px !important; }
            .col-img img { margin: 0 auto !important; width: 190px !important; height: auto !important; }
            .title { font-size: 30px !important; line-height: 38px !important; }
            .digit { width: 42px !important; height: 54px !important; font-size: 28px !important; line-height: 54px !important; }
            .digit-gap { width: 6px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#F1EEFB;">
@php
    // Khi gửi mail, Laravel truyền sẵn biến $message -> nhúng ảnh dạng CID (không cần ảnh public trên internet).
    $canEmbed = isset($message) && is_object($message) && method_exists($message, 'embed');
    $logoSrc = ($canEmbed && !empty($logoPath) && is_file($logoPath)) ? $message->embed($logoPath) : $logoUrl;
    $mascotSrc = ($canEmbed && !empty($mascotPath) && is_file($mascotPath)) ? $message->embed($mascotPath) : $mascotUrl;
@endphp

{{-- Preheader: dòng xem trước trong hộp thư --}}
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#F1EEFB; opacity:0;">
    {{ $isEnglish ? "Your verification code is {$code}. It expires in {$expireMinutes} minutes." : "Mã xác minh của bạn là {$code}, có hiệu lực trong {$expireMinutes} phút." }}
</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1EEFB;">
    <tr>
        <td align="center" style="padding:32px 12px;">

            <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" border="0"
                   style="width:600px; max-width:600px; background-color:#FFFFFF; border:1px solid #E4DCF7; border-radius:28px;">
                <tr>
                    <td class="card-pad font" style="padding:40px 40px 32px; font-family:'Be Vietnam Pro','Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

                        {{-- Hero: chữ bên trái, mascot bên phải --}}
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="col-text" valign="top" width="60%" style="width:60%; vertical-align:top; padding-right:12px;">
                                    <img src="{{ $logoSrc }}" width="96" alt="EDTIKA"
                                         style="width:96px; height:auto; margin-bottom:28px; color:#3F0687; font-size:22px; font-weight:800;">

                                    <h1 class="title" style="margin:0; font-size:34px; line-height:42px; font-weight:800; color:#3F0687; letter-spacing:-0.5px;">
                                        {{ $isEnglish ? 'Account registration successful' : 'Đăng ký tài khoản thành công' }}
                                    </h1>

                                    {{-- thanh gạch dưới 2 màu --}}
                                    <table role="presentation" class="rounded" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 24px; border-collapse:separate;">
                                        <tr>
                                            <td width="44" height="4" style="width:44px; height:4px; background-color:#3F0687; border-radius:4px; font-size:0; line-height:0;">&nbsp;</td>
                                            <td width="36" height="4" style="width:36px; height:4px; background-color:#D6CCF5; border-radius:4px; font-size:0; line-height:0;">&nbsp;</td>
                                        </tr>
                                    </table>

                                    <p style="margin:0 0 14px; font-size:17px; line-height:26px; color:#23202D;">
                                        {{ $isEnglish ? 'Hello' : 'Xin chào' }}
                                        <strong style="color:#3F0687; font-weight:800;">{{ $fullName ?: $email }}</strong>,
                                    </p>

                                    <p style="margin:0; font-size:15px; line-height:25px; color:#3A3745;">
                                        @if($isEnglish)
                                            Thank you for choosing {{ $siteName }}. Use the verification code below to complete your registration.
                                        @else
                                            Cảm ơn bạn đã lựa chọn {{ $siteName }}. Hãy sử dụng mã xác minh dưới đây để hoàn tất quá trình đăng ký.
                                        @endif
                                    </p>
                                </td>

                                <td class="col-img" valign="bottom" width="40%" align="right" style="width:40%; vertical-align:bottom; text-align:right;">
                                    <img src="{{ $mascotSrc }}" width="210" alt=""
                                         style="width:210px; max-width:100%; height:auto; margin-left:auto; border-radius:24px;">
                                </td>
                            </tr>
                        </table>

                        {{-- Mã OTP --}}
                        <table role="presentation" class="rounded" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:28px; border-collapse:separate;">
                            <tr>
                                <td align="center" style="background-color:#F6F3FD; border:1px solid #ECE6FA; border-radius:22px; padding:22px 12px;">
                                    <table role="presentation" class="rounded" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:separate;">
                                        <tr>
                                            @foreach($codeDigits as $digitIndex => $digit)
                                                @if($digitIndex > 0)
                                                    <td class="digit-gap" width="10" style="width:10px; font-size:0; line-height:0;">&nbsp;</td>
                                                @endif
                                                <td class="digit" align="center" valign="middle" width="64" height="76"
                                                    style="width:64px; height:76px; background-color:#FFFFFF; border:1px solid #E1D8F6; border-radius:14px;
                                                           font-family:'Be Vietnam Pro','Segoe UI',Roboto,Helvetica,Arial,sans-serif;
                                                           font-size:38px; line-height:76px; font-weight:800; color:#3F0687; text-align:center;">{{ $digit }}</td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:22px 0 0; text-align:center; font-size:16px; line-height:24px; color:#23202D;">
                            {{ $isEnglish ? 'This verification code is valid for' : 'Mã xác minh có hiệu lực trong' }}
                            <strong style="color:#23006C; font-weight:800; text-decoration:underline; text-underline-offset:3px;">
                                {{ $expireMinutes }} {{ $isEnglish ? 'minutes' : 'phút' }}.
                            </strong>
                        </p>

                        {{-- Đường kẻ --}}
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:28px 0 22px;">
                            <tr>
                                <td height="1" style="height:1px; background-color:#E4DCF7; font-size:0; line-height:0;">&nbsp;</td>
                            </tr>
                        </table>

                        {{-- Cảnh báo bảo mật --}}
                        <table role="presentation" class="rounded" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:separate;">
                            <tr>
                                <td valign="middle" width="30" align="center"
                                    style="width:30px; height:30px; background-color:#3F0687; border-radius:9px; color:#FFFFFF; font-size:14px; line-height:30px; text-align:center;">&#128274;</td>
                                <td width="12" style="width:12px; font-size:0;">&nbsp;</td>
                                <td valign="middle" style="font-size:14px; line-height:20px; color:#3A3745;">
                                    {{ $isEnglish ? 'Never share your OTP code with anyone.' : 'Không chia sẻ mã OTP với bất kỳ ai.' }}
                                </td>
                            </tr>
                        </table>

                        <p style="margin:18px 0 0; text-align:center; font-size:12px; line-height:18px; color:#8C88A0;">
                            @if($isEnglish)
                                If you did not create an account with {{ $email }}, please ignore this email.
                            @else
                                Nếu bạn không đăng ký tài khoản bằng {{ $email }}, vui lòng bỏ qua email này.
                            @endif
                        </p>
                    </td>
                </tr>
            </table>

            <p class="font" style="margin:18px 0 0; text-align:center; font-size:12px; line-height:18px; color:#9A95B0; font-family:'Be Vietnam Pro','Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
                &copy; {{ date('Y') }} {{ $siteName }}
            </p>
        </td>
    </tr>
</table>
</body>
</html>
