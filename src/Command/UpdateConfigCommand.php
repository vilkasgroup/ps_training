<?php

namespace Invertus\Training\Command;

use Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateConfigCommand extends Command
{

    /**
     * Example of how to setup command from module.
     * Command could be used by going to PS folder and writing bin/console training:update-configuration arg1 arg2
     * Could be that you need to add php before that depending on a sytem so: php bin/console training:update-configuration arg1 arg2
     * Arguments are defined using addArgument functions
     *
     */
    protected function configure()
    {
        $this
            ->setName('training:update-configuration')
            ->setDescription('Updated configuration')
            ->addArgument('id_configuration', InputArgument::REQUIRED)
            ->addArgument('value', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $input->getArgument('id_configuration');
        $value = $input->getArgument('value');
        Configuration::updateValue($configuration, $value);
        $output->write('It worked');
    }
}
