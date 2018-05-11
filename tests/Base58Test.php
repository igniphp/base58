<?php declare(strict_types=1);

namespace IgniTest\Unit\Crypto;

use Igni\Crypto\Base58;
use PHPUnit\Framework\TestCase;

class Base58Test extends TestCase
{
    public function testEncodeAndDecode(): void
    {
        for ($i = 0; $i < 1000; $i++) {
            $string = $this->generateRandomString(30);
            $encoded = Base58::encode($string);
            $decoded = Base58::decode($encoded);

            self::assertSame($decoded, $string);
            self::assertEmpty(array_diff(array_unique(str_split($encoded)), str_split(Base58::SIGNATURE)));
        }
    }

    private function generateRandomString($length = 10)
    {
        $characters = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*&^!%@*<>:"\'\\//';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

