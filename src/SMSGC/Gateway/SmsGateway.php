<?php

namespace SMSGC\Gateway;

use GuzzleHttp\Client;

/**
 * Sms Gateway integration.
 */
class SmsGateway {

    protected $baseUrl;
    protected $email;
    protected $password;
    protected $device;

    /**
     * @param string $email
     * @param string $password
     * @param int $device
     * @param string $baseUrl
     */
    public function __construct($email, $password, $device, $baseUrl = 'https://smsgateway.me') {
        $this->baseUrl  = $baseUrl;
        $this->email    = $email;
        $this->password = $password;
        $this->device   = $device;
    }

    /**
     * Send a message.
     *
     * @param array $to
     * @param string $message
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendMessage(array $to, $message, $device = 0) {
        $fields = [
            'number' => $to,
            'message' => $message,
            'device' =>  !$device ? $this->device : $device,
        ];

        return $this->postRequest('/api/v3/messages/send', $fields);
    }

    /**
     * Get messages.
     *
     * @param int $device
     * @param int $page
     *
     * @return array
     */
    public function getMessages($device = 0, $page = 1) {
        $response = $this->getRequest('/api/v3/messages', ['page' => $page]);
        $messages = json_decode($response->getBody(), true);
        $result = $messages['result'];

        if ($device) {
            $result = array_filter($result, function($item) use ($device) {
                return $device === (int) $item['device_id'];
            });
        }

        return $result;
    }

    /**
     * Make a POST request.
     *
     * @param string $path
     * @param array $fields
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function postRequest($path, array $fields = []) {
        $options = [
            'form_params' => [
                'email' => $this->email,
                'password' => $this->password,
            ] + $fields,
            'verify' => false,
        ];

        $client = new Client(['base_uri' => $this->baseUrl]);
        return $client->post($path, $options);
    }

    /**
     * Make a GET request.
     *
     * @param string $path
     * @param $fields
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function getRequest($path, array $fields = []) {
        $options = [
            'query' => [
                'email' => $this->email,
                'password' => $this->password,
            ] + $fields,
            'verify' => false,
        ];

        $client = new Client(['base_uri' => $this->baseUrl]);
        return $client->get($path, $options);
    }
}
