<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 25.09.16
 * Time: 10:48.
 */
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FinishDuelsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mkr:duels:finish')
            ->setDescription('Finish duels');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('app.handler.duel')->finishDuels();
    }
}
