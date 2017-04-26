<?php
namespace Yoanm\InitRepositoryWithComposer\Domain\Model;

class Script
{
    /** @var string */
    private $name;
    /** @var string */
    private $command;

    /**
     * @param string $name
     * @param string $command
     */
    public function __construct($name, $command)
    {
        $this->name = $name;
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }
}
