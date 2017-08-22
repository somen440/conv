<?php

namespace Conv\Command;

use Conv\Factory\ViewStructureFactory;
use Conv\Generator\MigrationGenerator;
use Conv\Generator\TableAlterMigrationGenerator;
use Conv\Util\Operator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class CheckViewCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('check:view');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $operator = $this->getOperator($input, $output);

        // $actualStructure = ViewStructureFactory::fromView(
        //     $this->getPDO('conv'),
        //     'conv',
        //     'view_user'
        // );
        // dump($actualStructure);
        $spec = Yaml::parse(file_get_contents('schema/view_user.yml'));
        $expectStructure = ViewStructureFactory::fromSpec('view_user', $spec);
        // dump($expectStructure);
        dump($expectStructure->getJoinStructure()->genareteJoinQuery());
    }
}