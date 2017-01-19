<?php

namespace SMSGC\Gateway;

use GuzzleHttp\Client;

/**
 * Sms Gateway integration.
 */
class SmsGateway implements GatewayInterface {

    protected $baseUrl;
    protected $email;
    protected $password;
    protected $device;

    /**
     * @param string $baseUrl
     * @param string $email
     * @param string $password
     * @param int $device
     */
    public function __construct($email, $password, $device, $baseUrl = "https://smsgateway.me") {
        $this->baseUrl  = $baseUrl;
        $this->email    = $email;
        $this->password = $password;
        $this->device   = $device;
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage($to, $message) {
        $fields = [
            'contact' => $to,
            'message' => $message,
        ];

        return $this->makeRequest('/api/v3/messages/send', 'POST', $fields);
    }

    /**
     * Make a request.
     *
     * @param string $path
     * @param string $method
     * @param array $fields
     */
    private function makeRequest($path, $method, array $fields = []) {
        $options = [
            'form_params' => [
                'email' => $this->email,
                'password' => $this->password,
                'device' => $this->device,
                'number' => $fields['contact'],
                'message' => $fields['message'],
            ],
            'verify' => false,
        ];

        $client = new Client(['base_uri' => $this->baseUrl]);
        return $client->request($method, $path, $options);
    }
}
