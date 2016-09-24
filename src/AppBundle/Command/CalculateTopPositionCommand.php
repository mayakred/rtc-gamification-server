<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 24.09.16
 * Time: 21:24.
 */
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateTopPositionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mkr:calc-top-position')
            ->setDescription('Recalculate top position');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('app.manager.user')->recalcUserPosition();
    }
}
