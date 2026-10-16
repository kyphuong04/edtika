<?php

namespace App\Models;

use App\Notifications\SendVerificationEmailCode;
use App\Notifications\SendVerificationSMSCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Verification extends Model
{
    use Notifiable;

    protected $table = 'verifications';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    const EXPIRE_TIME = 3600; // second => 1 hour

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // public function sendEmailCode()
    // {
    //     if (app()->environment('production')) {
    //         $this->notify(new SendVerificationEmailCode($this));
    //     }

    //     return true;
    // }

    // public function sendSMSCode()
    // {
    //     if (app()->environment('production')) {
    //         $this->notify(new SendVerificationSMSCode($this));
    //     }
    // }

    // public function sendEmailCode()
    // {
    //     if (config('services.verification.enabled', true)) {
    //         $this->notify( new SendVerificationEmailCode($this));
    //     }
    //     return true;
    // }

    // public function sendSMSCode()
    // {
    //     $this->notify(new SendVerificationSMSCode($this));
    // }

    public function sendEmailCode()
    {
        try {
            $this->notify(new SendVerificationEmailCode($this));
        } catch (\Throwable $exception) {
            // Không để lỗi SMTP làm hỏng luồng đăng ký (user đã được tạo);
            // người dùng vẫn có thể bấm "Gửi lại mã" trong modal.
            Log::error('Send verification email failed', [
                'verification_id' => $this->id,
                'email' => $this->email,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }

        return true;
    }

    public function sendSMSCode()
    {
        $this->notify(new SendVerificationSMSCode($this));
    }
}


