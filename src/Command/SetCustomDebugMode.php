<?php

namespace Invertus\Training\Command;

use Configuration;
use Invertus\Training\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetCustomDebugMode extends Command
{
    protected function configure()
    {
        $this->setName('training:set-custom-debug')
            ->setDescription('Update debug mode')
            ->addArgument('active', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $active = $input->getArgument('active');
        if ($active != '0' && $active != '1') {
            $output->writeln('Active must be 0 or 1');
            return;
        }

        Configuration::updateValue(Config::CUSTOM_DEBUG_MODE, $active);
        $output->writeln('Configuration successfully updated');

    }
}
