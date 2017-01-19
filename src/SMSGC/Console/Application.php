<?php

namespace SMSGC\Console;

use SMSGC\Command\SendMessageCommand;
use SMSGC\Command\InitCommand;
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
