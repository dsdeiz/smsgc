<?php

namespace SMSGC\Command;

use SMSGC\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->setHelp('Send a message to a number(s).')
            ->addArgument('message', InputArgument::REQUIRED, 'The message to send.')
            ->addArgument('to', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Number(s) to send the message to.')
            ->addOption('device', 'd', InputOption::VALUE_OPTIONAL, 'The device ID if not the default.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $gateway = $this->getGateway();
        $gateway->sendMessage($input->getArgument('to'), $input->getArgument('message'));

        $io = new SymfonyStyle($input, $output);
        $io->success('Successfully sent message.');
    }
}
