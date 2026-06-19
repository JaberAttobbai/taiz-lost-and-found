<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * إشعار إعادة تعيين كلمة المرور — يُرسل عبر Queue لتجنب Timeout.
 */
class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;
}
