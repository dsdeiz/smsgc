<?php

namespace SMSGC\Config;

/**
 * Write a config file.
 */
class Config {

    const PASSWORD = 'smsgc';

    private $path;

    /**
     * Initialize.
     *
     * @param string $path
     */
    public function __construct($path = '') {
        if (empty($path)) {
            $path = getenv('HOME') . '/.smsgc';
        }

        $this->path = $path;
    }

    /**
     * Encode a value into JSON format.
     *
     * @param mixed $data
     * @param int $options
     * 
     * @return string
     */
    public function encode($data, $options = JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) {
        return json_encode($data, $options);
    }

    /**
     * Decode a JSON string.
     *
     * @param string $json
     *
     * @return mixed
     */
    public function decode($json) {
        return json_decode($json, true);
    }

    /**
     * Read config file.
     *
     * @return mixed
     */
    public function read() {
        $json = file_get_contents($this->path);
        $data = $this->decode($json);
        $data['password'] = static::decrypt($data['password']);
        return $data;
    }

    /**
     * Write config file.
     *
     * @param array $data
     * @param int $options
     */
    public function write(array $data, $options = JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) {
        file_put_contents($this->path, $this->encode($data, $options) . ($options & JSON_PRETTY_PRINT ? "\n" : ''));
    }

    /**
     * Encrypt string for use in config files.
     *
     * @param string $data
     * @param string $cipher
     *
     * @return string
     */
    public static function encrypt($data, $cipher = 'AES-128-CBC') {
        $iv = random_bytes(16);
        $value = openssl_encrypt($data, $cipher, self::PASSWORD, 0, $iv);

        $data = [
            'iv' => base64_encode($iv),
            'value' => $value,
        ];

        return base64_encode(json_encode($data));
    }

    /**
     * Decrypt string for use in config files.
     *
     * @param string $data
     * @param string $cipher
     *
     * @return string
     */
    public static function decrypt($data, $cipher = 'AES-128-CBC') {
        $data = json_decode(base64_decode($data), true);
        return openssl_decrypt($data['value'], $cipher, self::PASSWORD, 0, base64_decode($data['iv']));
    }
}
