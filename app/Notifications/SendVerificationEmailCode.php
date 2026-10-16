<?php

namespace App\Notifications;

use App\Models\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendVerificationEmailCode extends Notification
{
    use Queueable;

    private $notifiable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable  App\Models\Verification
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $generalSettings = getGeneralSettings();
        $isEnglish = mb_strtolower(app()->getLocale()) === 'en';

        $user = $notifiable->user ?? null;
        $fullName = !empty($user) && !empty($user->full_name) ? $user->full_name : null;

        $code = (string)$notifiable->code;
        $expireMinutes = (int)ceil(Verification::EXPIRE_TIME / 60);

        $subject = $isEnglish
            ? "{$code} is your EDTIKA verification code"
            : "{$code} là mã xác minh tài khoản EDTIKA của bạn";

        return (new MailMessage)
            ->subject($subject)
            ->from(!empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->view('design_1.web.emails.register_otp', [
                'isEnglish' => $isEnglish,
                'fullName' => $fullName,
                'email' => $notifiable->email,
                'code' => $code,
                'codeDigits' => preg_split('//u', $code, -1, PREG_SPLIT_NO_EMPTY),
                'expireMinutes' => $expireMinutes,
                'siteName' => !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : 'EDTIKA',
                // Ảnh được nhúng thẳng vào email (CID) nên hiển thị được cả khi web chạy ở domain local.
                // URL chỉ dùng khi xem trước template ngoài luồng gửi mail.
                'logoPath' => public_path('assets/design_1/img/emails/edtika-logo.png'),
                'mascotPath' => public_path('assets/design_1/img/emails/edtika-otp-mascot.jpg'),
                'logoUrl' => asset('assets/design_1/img/emails/edtika-logo.png'),
                'mascotUrl' => asset('assets/design_1/img/emails/edtika-otp-mascot.jpg'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
