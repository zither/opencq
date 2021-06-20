<?php

namespace Xian;

class Encoder implements EncoderInterface
{
    /**
     * @var string 请自行修改该密钥
     */
    protected $secret = '4e44e29997f15858q282a3d12ce53116';

    /**
     * @var bool
     */
    protected $padding = true;

    /**
     * encode constructor.
     * @param string|null $secret
     */
    public function __construct(string $secret = null, bool $padding = true)
    {
        if (!empty($secret)) {
            $this->secret = $secret;
        }
        $this->padding = $padding;
    }

    /**
     * @param string $string
     * @return string
     */
    public function encode(string $string): string
    {
        if ($this->padding) {
            parse_str($string, $queries);
            $paddingQueries = $this->padQueries($queries);
            $string = http_build_query($paddingQueries);
        }
        $cipherText = openssl_encrypt(
            $string,
            'RC4-40',
            $this->secret,
            OPENSSL_RAW_DATA|OPENSSL_NO_PADDING
        );
        return $this->base64Encode($cipherText);
    }

    /**
     * @param string $string
     * @return string
     */
    public function decode(string $string): string
    {
        $cipherText = $this->base64Decode($string);
        return openssl_decrypt(
            $cipherText,
            'RC4-40',
            $this->secret,
            OPENSSL_RAW_DATA|OPENSSL_NO_PADDING
        );
    }

    /**
     * URL safe base64 encoder
     * @param string $data
     * @return string
     */
    protected function base64Encode(string $data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * URL safe base64 decoder
     * @param string $data
     * @return bool|string
     */
    protected function base64Decode(string $data)
    {
        $data = str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($data);
    }

    /**
     * @param int $bytes
     * @return bool|string
     */
    protected function randomString(int $bytes)
    {
        try {
            return bin2hex(random_bytes($bytes));
        } catch (\Exception $e) {
            return '';
        }
    }

    protected function padQueries(array $queries)
    {
        try {
            $num = random_int(1, 3);
            for ($i = 0; $i < $num; $i++) {
                $key = sprintf('_%s', $this->randomString(2));
                $queries[$key] = random_int(0, 100);
            }
            $paddingQueries = [];
            $keys = array_keys($queries);
            shuffle($keys);
            foreach ($keys as $k) {
                $paddingQueries[$k]  = $queries[$k];
            }
            return $paddingQueries;
        } catch (\Exception $e) {
            return $queries;
        }
    }
}