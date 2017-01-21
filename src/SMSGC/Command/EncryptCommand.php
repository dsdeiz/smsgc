<?php

namespace SMSGC\Command;

use SMSGC\Command\BaseCommand;
use SMSGC\Config\Config;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Encrypt password.
 */
class EncryptCommand extends BaseCommand {

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
            ->setName('encrypt')
            ->setDescription('Encrypts a text.')
            ->setHelp('Encrypts a text for use in the configuration file - password.')
            ->addArgument('text', InputArgument::REQUIRED, 'The text to encrypt.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln(Config::encrypt($input->getArgument('text')));
    }
}
