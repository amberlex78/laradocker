<?php

namespace App\Services;

use App\Models\LoginSession;
use Carbon\CarbonInterface;

/**
 * Immutable request snapshot used by login-session status decisions.
 */
final readonly class LoginSessionStatusContext
{
    public function __construct(
        public ?string $currentLoginJournalId,
        public string $currentHttpSessionId,
        public CarbonInterface $inactivityThreshold,
    ) {}

    /**
     * Capture the request values needed by status resolution at one point in time.
     */
    public static function fromCurrentRequest(): self
    {
        $currentLoginJournalId = session()->get(LoginSession::SESSION_KEY);

        return new self(
            currentLoginJournalId: $currentLoginJournalId === null ? null : (string) $currentLoginJournalId,
            currentHttpSessionId: session()->getId(),
            inactivityThreshold: now()->subMinutes((int) config('session.lifetime')),
        );
    }
}
