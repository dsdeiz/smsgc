<?php

namespace SMSGC\Command;

use SMSGC\Command\BaseCommand;
use SMSGC\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Generates the configuration.
 */
class InitCommand extends BaseCommand {

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
            ->setName('init')
            ->setDescription('Creates a basic configuration file.')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email address')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Password')
            ->addOption('device', null, InputOption::VALUE_OPTIONAL, 'Device');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $whitelist = [
            'email',
            'password',
            'device',
        ];

        $options = array_filter(array_intersect_key($input->getOptions(), array_flip($whitelist)));
        $options['password'] = Config::encrypt($options['password']);

        $config = new Config();
        $config->write($options);

        $output->writeln('<info>Successfully generated configuration file.</info>');
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask(
            'Email',
            null,
            function($email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \RuntimeException('Invalid email address.');
                }

                return $email;
            }
        );

        $input->setOption('email', $email);

        $password = $io->askHidden('Password');
        $input->setOption('password', $password);

        $device = $io->ask(
            'Default device ID',
            null,
            function($device) {
                if (!is_numeric($device)) {
                    throw new \RuntimeException('Invalid device ID.');
                }

                return $device;
            }
        );

        $input->setOption('device', $device);
    }
}
