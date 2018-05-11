<?php declare(strict_types=1);

namespace Igni\Crypto;

use InvalidArgumentException;

/**
 * Used to encode/decode strings into representation of large integers as alphanumeric text.
 *
 * Credits go to:
 * @see https://github.com/stephen-hill/base58php/blob/master/src/BCMathService.php
 */
final class Base58
{
    public const SIGNATURE = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

    private const BASE58_LENGTH = '58';

    private const BASE256_LENGTH = '256';

    /**
     * Encodes passed integer|string into base58 representation.
     *
     * @param string|int $string
     * @return string
     */
    public static function encode($string): string
    {
        $string = (string) $string;
        // If string is empty base is empty as well.
        if (empty($string)) {
            return '';
        }

        $bytes = array_values(array_map(function($byte) { return (string) $byte; }, unpack('C*', $string)));
        $base10 = $bytes[0];

        // Convert string into base 10
        for ($i = 1, $l = count($bytes); $i < $l; $i++) {
            $base10 = bcmul($base10, self::BASE256_LENGTH);
            $base10 = bcadd($base10, $bytes[$i]);
        }

        // Convert base 10 to base 58 string
        $base58 = '';
        while ($base10 >= self::BASE58_LENGTH) {
            $div = bcdiv($base10, self::BASE58_LENGTH, 0);
            $mod = bcmod($base10, self::BASE58_LENGTH);
            $base58 .= self::SIGNATURE[$mod];
            $base10 = $div;
        }
        if ($base10 > 0) {
            $base58 .= self::SIGNATURE[$base10];
        }

        // Base 10 to Base 58 requires conversion
        $base58 = strrev($base58);

        // Add leading zeros
        foreach ($bytes as $byte) {
            if ($byte === '0') {
                $base58 = self::SIGNATURE[0] . $base58;
                continue;
            }
            break;
        }

        return $base58;
    }

    /**
     * Decodes base58 representation of big integer into string.
     *
     * @param string $base58
     * @return string
     */
    public static function decode(string $base58): string
    {
        if (empty($base58)) {
            return '';
        }

        $indexes = array_flip(str_split(self::SIGNATURE));
        $chars = str_split($base58);

        // Check for invalid characters in the supplied base58 string
        foreach ($chars as $char) {
            if (isset($indexes[$char]) === false) {
                throw new InvalidArgumentException('Argument $base58 contains invalid characters. ($char: "'.$char.'" | $base58: "'.$base58.'") ');
            }
        }

        // Convert from base58 to base10
        $decimal = (string) $indexes[$chars[0]];

        for ($i = 1, $l = count($chars); $i < $l; $i++) {
            $decimal = bcmul($decimal, self::BASE58_LENGTH);
            $decimal = bcadd($decimal, (string) $indexes[$chars[$i]]);
        }

        // Convert from base10 to base256 (8-bit byte array)
        $output = '';
        while ($decimal > 0) {
            $byte = bcmod($decimal, self::BASE256_LENGTH);
            $output = pack('C', $byte) . $output;
            $decimal = bcdiv($decimal, self::BASE256_LENGTH, 0);
        }

        // Now we need to add leading zeros
        foreach ($chars as $char) {
            if ($indexes[$char] === 0) {
                $output = "\x00" . $output;
                continue;
            }
            break;
        }

        return $output;
    }
}
