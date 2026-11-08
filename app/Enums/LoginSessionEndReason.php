<?php

namespace App\Enums;

enum LoginSessionEndReason: string
{
    case LoggedOut = 'logged_out';
    case Terminated = 'terminated';
}
