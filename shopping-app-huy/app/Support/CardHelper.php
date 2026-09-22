<?php

namespace App\Support;

/**
 * Turns a card number the buyer typed into ONLY the last 4 digits + a
 * guessed brand — nothing else about the card is ever kept. Callers
 * should use $result and then let the raw $cardNumber variable go out
 * of scope; it is never written to the database, session, cache, or logs.
 */
class CardHelper
{
    public static function deriveDisplayInfo(string $cardNumber): array
    {
        $digits = preg_replace('/\D/', '', $cardNumber);

        return [
            'brand' => self::guessBrand($digits),
            'last4' => substr($digits, -4),
        ];
    }

    private static function guessBrand(string $digits): string
    {
        return match (true) {
            str_starts_with($digits, '4') => 'Visa',
            str_starts_with($digits, '5') => 'Mastercard',
            str_starts_with($digits, '34') || str_starts_with($digits, '37') => 'Amex',
            default => 'Card',
        };
    }
}
