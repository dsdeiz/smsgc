<?php

namespace SMSGC\Command;

use SMSGC\Command\BaseCommand;
use SMSGC\Config\Config;
use SMSGC\Gateway\SmsGateway;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Send message command.
 */
class SendMessageCommand extends BaseCommand {

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
            ->setName('send')
            ->setDescription('Sends a message.')
            ->setHelp('Send a message to a number.')
            ->addArgument('to', InputArgument::REQUIRED, 'Number to send the message to.')
            ->addArgument('message', InputArgument::REQUIRED, 'The message to send.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);
        $io->success('Successfully sent message to ' . $input->getArgument('to') . '.');

        $config = new Config();
        $config = $config->read();

        $gateway = new SmsGateway($config['email'], $config['password'], $config['device']);
        // $gateway->sendMessage('+639399124230', 'Wat');
    }
}
