<?php

namespace AppBundle\Command;

use AppBundle\DBAL\Types\TournamentType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTournamentCommand extends ContainerAwareCommand
{
    const PATH = 'src/AppBundle/DataFixtures/ORM/Realistic';

    protected function configure()
    {
        $this
            ->setName('mkr:tournament:create')
            ->setDescription('Create new tournament')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Tournament name')
            ->addOption('period', null, InputOption::VALUE_REQUIRED, 'Tournament period in days')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Tournament type in days')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     *
     * @return array|\object[]
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('name');

        $period = $input->getOption('period');
        if (!is_numeric($period) || (int) $period < 1) {
            $output->writeln('<error>Period must be positive</error>');

            return;
        }

        $type = $input->getOption('type');
        if (!in_array($type, [TournamentType::TEAM, TournamentType::INDIVIDUAL], true)) {
            $output->writeln("<error>Incorrect type ['team', 'individual']</error>");

            return;
        }

        $this->getContainer()->get('app.manager.tournament')->add($name, $type, $period);
    }
}
