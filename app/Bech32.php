<?php

namespace App;

class Bech32
{
    protected const CHARSET = "qpzry9x8gf2tvdw0s3jn54khce6mua7l";

    protected const GENERATOR = [0x3b6a57b2, 0x26508e6d, 0x1ea119fa, 0x3d4233dd, 0x2a1462b3];

    public const ENCODINGS = [
        'original' => "bech32",
        'modified' => "bech32m",
    ];


    /**
     * @param $enc
     *
     * @return int|null
     */
    protected function getEncodingConst($enc): ?int
    {
        if ($enc === self::ENCODINGS['original']) {
            return 1;
        }

        if ($enc === self::ENCODINGS['modified']) {
            return 0x2bc830a3;
        }

        return null;
    }

    /**
     * @param  array  $values
     *
     * @return int
     */
    protected function polyMod(array $values): int
    {
        $chk = 1;

        foreach ($values as $pValue) {
            $top = $chk >> 25;
            $chk = ($chk & 0x1ffffff) << 5 ^ $pValue;

            for ($i = 0; $i < 5; $i++) {
                if (($top >> $i) & 1) {
                    $chk ^= self::GENERATOR[$i];
                }
            }
        }

        return $chk;
    }

    /**
     * @param  string  $hrp
     *
     * @return int[]
     */
    protected function hrpExpand(string $hrp): array
    {
        $length = strlen($hrp);
        $ret = [];

        for ($p = 0; $p < $length; ++$p) {
            $ret[] = ord($hrp[$p]) >> 5;
        }

        $ret[] = 0;

        for ($p = 0; $p < $length; ++$p) {
            $ret[] = ord($hrp[$p]) & 31;
        }

        return $ret;
    }

    /**
     * @param  string  $hrp
     * @param  array   $data
     * @param  string  $enc
     *
     * @return bool
     */
    protected function verifyChecksum(string $hrp, array $data, string $enc): bool
    {
        $values = array_merge($this->hrpExpand($hrp), $data);

        return $this->polyMod($values) === $this->getEncodingConst($enc);
    }

    /**
     * @param  string  $bechString
     * @param  string  $enc
     *
     * @return array|null
     */
    public function decode(string $bechString, string $enc): ?array
    {
        $length = strlen($bechString);
        $has_lower = false;
        $has_upper = false;

        for ($p = 0; $p < $length; ++$p) {
            $char = ord($bechString[$p]);

            if ($char < 33 || $char > 126) {
                return null;
            }

            if ($char >= 97 && $char <= 122) {
                $has_upper = true;
            }

            if ($char >= 65 && $char <= 90) {
                $has_lower = true;
            }
        }

        if ($has_lower && $has_upper) {
            return null;
        }

        $bechString = strtolower($bechString);
        $pos = strpos($bechString, 1);

        if ($pos < 1 || ($pos + 7) > $length || $length > 110) {
            return null;
        }

        $hrp = substr($bechString, 0, $pos);
        $data = [];

        for ($p = $pos + 1; $p < $length; ++$p) {
            $d = strpos(self::CHARSET, $bechString[$p]);

            if ($d === -1) {
                return null;
            }

            $data[] = $d;
        }

        if (! $this->verifyChecksum($hrp, $data, $enc)) {
            return null;
        }

        return [
            'hrp'  => $hrp,
            'data' => array_slice($data, 0, -6),
        ];
    }
}
