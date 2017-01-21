<?php

namespace SMSGC\Console;

use SMSGC\Command\EncryptCommand;
use SMSGC\Command\GetMessagesCommand;
use SMSGC\Command\InitCommand;
use SMSGC\Command\SendMessageCommand;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Base application.
 */
final class Application extends BaseApplication {

    /**
     * {@inheritdoc}
     */
    public function __construct() {
        parent::__construct('SMSGC', '0.0.1');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCommands() {
        $commands = parent::getDefaultCommands();

        $commands[] = new SendMessageCommand();
        $commands[] = new InitCommand();
        $commands[] = new EncryptCommand();
        $commands[] = new GetMessagesCommand();

        return $commands;
    }

    /**
     * Factory method.
     *
     * @return Application
     */
    public static function create() {
        return new static();
    }
}
