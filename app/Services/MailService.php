<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\Register;
use App\Mail\Bind;
use App\Mail\Password;
class MailService
{

    public static function sendMail($to,$data,$view)
    {
        switch  ($view)
        {
            case 'signup':
                Mail::to($to)->send(new Register($data));
                break;
            case 'bind':
                Mail::to($to)->send(new Bind($data));
                break;
            case 'reset':
                Mail::to($to)->send(new Password($data));
                break;
            default:
                return 'error';
        }

    }
}
