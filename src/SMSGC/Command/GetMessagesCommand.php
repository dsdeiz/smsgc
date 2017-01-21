<?php

namespace SMSGC\Command;

use SMSGC\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Get messages.
 */
class GetMessagesCommand extends BaseCommand {

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
            ->setName('messages')
            ->setDescription('Retrieves messages.')
            ->setHelp('Retrieve messages.')
            ->addArgument('device', InputArgument::OPTIONAL, 'The device ID to filter.')
            ->addArgument('page', InputArgument::OPTIONAL, 'The page to view.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $gateway = $this->getGateway();
        $messages = $gateway->getMessages($input->getArgument('device'), $input->getArgument('page'));

        $io = new SymfonyStyle($input, $output);
        $io->table(
            ['ID', 'Message', 'Contact'],
            $this->processMessages($messages)
        );
    }

    /**
     * Process message.
     *
     * @param array $messages
     *
     * @return array $items
     */
    private function processMessages(array $messages = []) {
        $items = [];

        foreach ($messages as $message) {
            $items[] = [
                $message['id'],
                $this->truncateMessage($message['message']),
                $message['contact']['name'],
            ];
        }

        return $items;
    }

    /**
     * Truncate text.
     *
     * @param string $message
     *
     * @return string
     */
    private function truncateMessage($message) {
        return $this->getHelper('formatter')->truncate($message, 24);
    }
}
