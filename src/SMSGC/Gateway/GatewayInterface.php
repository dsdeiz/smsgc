<?php

namespace SMSGC\Gateway;

interface GatewayInterface {

    /**
     * Send a message.
     *
     * @param string $to
     * @param string $message
     */
    public function sendMessage($to, $message);
}
