<?php

namespace App\Services;

use App\Enums\LoginSessionStatus;
use App\Models\LoginSession;

/**
 * Purely determines a login-session status from a record and request snapshot.
 */
final class LoginSessionStatusResolver
{
    /**
     * Apply status precedence from terminal state through current-session matching.
     */
    public function resolveStatus(LoginSession $loginSession, LoginSessionStatusContext $context): LoginSessionStatus
    {
        return match (true) {
            $loginSession->end_reason?->isTermination() === true => LoginSessionStatus::Terminated,
            $loginSession->logged_out_at !== null => LoginSessionStatus::SignedOut,
            $loginSession->expires_at->lt($context->currentTime) => LoginSessionStatus::Expired,
            $loginSession->getKey() === $context->currentLoginJournalId
                || ($loginSession->session_id !== null
                    && $loginSession->session_id === $context->currentHttpSessionId) => LoginSessionStatus::Current,
            default => LoginSessionStatus::Active,
        };
    }
}
