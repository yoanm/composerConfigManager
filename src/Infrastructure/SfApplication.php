<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class SfApplication extends Application
{
    /**
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct();
        $this->add($command);
        $this->setDefaultCommand($command->getName(), true);
    }
}
