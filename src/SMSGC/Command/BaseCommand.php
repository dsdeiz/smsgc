<?php

namespace SMSGC\Command;

use SMSGC\Config\Config;
use SMSGC\Gateway\SmsGateway;
use Symfony\Component\Console\Command\Command;

/**
 * Base command.
 */
abstract class BaseCommand extends Command {

    private $config;
    private $gateway;

    /**
     * Retrieve the config.
     */
    public function getConfig() {
        if (null === $this->config) {
            $this->config = new Config();
        }

        return $this->config;
    }

    /**
     * Retrieve the gateway.
     */
    public function getGateway() {
        if (null === $this->gateway) {
            $this->getConfig();
            $config = $this->config->read();

            $this->gateway = new SmsGateway(
                $config['email'],
                $config['password'],
                $config['device']
            );
        }

        return $this->gateway;
    }
}
