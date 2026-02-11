<?php

namespace App\Libraries;

class TOTP
{
    /**
     * Generate a secret key
     */
    public function generateSecret($length = 16)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';  // Base32 alphabet
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Calculate the code for a given secret and time slice
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = $this->base32_decode($secret);

        // Pack time into binary string
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);

        $hmac = hash_hmac('sha1', $time, $secretkey, true);
        $offset = ord(substr($hmac, -1)) & 0xF;
        $hashpart = substr($hmac, $offset, 4);

        $value = unpack('N', $hashpart);
        $value = $value[1];
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, 6);
        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a code
     */
    public function verifyCode($secret, $code, $discrepancy = 1)
    {
        $currentTimeSlice = floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($calculatedCode === $code) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generate Google Authenticator QR Code URL
     * Note: This uses the googleapis chart API which is deprecated but still widely used,
     * or we can just return the otpauth:// URI for a JS library to render.
     * Let's return the URI and data.
     */
    public function getProvisioningUri($secret, $name, $issuer = null)
    {
        $issuer = $issuer !== null ? '&issuer=' . urlencode($issuer) : '';
        return 'otpauth://totp/' . urlencode($name) . '?secret=' . $secret . $issuer;
    }

    /**
     * Helper to decode base32
     */
    private function base32_decode($base32)
    {
        $base32 = strtoupper($base32);
        $l = strlen($base32);
        $n = 0;
        $j = 0;
        $binary = '';

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

        for ($i = 0; $i < $l; $i++) {
            $n = $n << 5;
            $n = $n + strpos($alphabet, $base32[$i]);
            $j = $j + 5;

            if ($j >= 8) {
                $j = $j - 8;
                $binary .= chr(($n & (0xFF << $j)) >> $j);
            }
        }

        return $binary;
    }
}
